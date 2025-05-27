<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\AssociationRule;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Order;
use App\Models\ProductOption;
use App\Models\ProductCombination;
use Illuminate\Support\Facades\DB;
use Phpml\Association\Apriori;

class ProductController extends Controller
{

    /**
     * Metode untuk menjalankan analisis Apriori dan menyimpan hasilnya.
     * Ini bisa dipicu secara manual via URL di lingkungan lokal Anda.
     *
     * @return \Illuminate\Http\Response
     */
    public function runAprioriAnalysis()
    {
        // --- PERINGATAN ---
        // Untuk produksi, sangat disarankan untuk memindahkan logika ini ke Laravel Command
        // dan menjadwalkannya (Scheduling) atau menggunakan Queue untuk menghindari timeout
        // dan menjaga performa aplikasi web Anda. Ini hanya untuk kemudahan di lokal.
        // --- ---------- ---

        // 1. Ambil data transaksi mentah dari database
        $orders = Order::with('details.productCombination.product')->get();

        $transactions = [];
        // Buat mapping Product Name -> Product ID untuk konversi saat menyimpan
        $productNameToIdMap = Product::pluck('id', 'name')->toArray();

        foreach ($orders as $order) {
            $itemsInTransaction = [];
            foreach ($order->details as $detail) {
                // Pastikan relasi ditemukan dan produk ada
                if ($detail->productCombination && $detail->productCombination->product) {
                    $productName = $detail->productCombination->product->name;
                    $itemsInTransaction[] = $productName;
                }
            }
            if (!empty($itemsInTransaction)) {
                $transactions[] = $itemsInTransaction;
            }
        }

        if (empty($transactions)) {
            return response("Tidak ada transaksi ditemukan untuk dianalisis.", 200);
        }

        // 2. Jalankan Algoritma Apriori menggunakan PHP-ML
        try {
            // Panggil fungsi baru untuk PHP-ML
            $processedRules = $this->getAprioriRulesFromPhpMl($transactions);
        } catch (\Exception $e) {
            \Log::error("PHP-ML Apriori Training Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response("Terjadi kesalahan saat menjalankan algoritma Apriori: " . $e->getMessage(), 500);
        }

        if (empty($processedRules)) {
            return response("Tidak ada aturan asosiasi ditemukan dengan ambang batas saat ini setelah pemrosesan.", 200);
        }

        // 3. Simpan Aturan ke Database
        AssociationRule::truncate(); // Hapus semua aturan lama sebelum memulai transaksi baru

        DB::beginTransaction(); // Mulai transaksi untuk operasi INSERT
        try {
            foreach ($processedRules as $rule) {
                // Konversi nama produk di antecedent ke ID produk
                $antecedentProductIds = [];
                foreach ($rule['antecedent'] as $name) {
                    if (isset($productNameToIdMap[$name])) {
                        $antecedentProductIds[] = $productNameToIdMap[$name];
                    }
                }

                // Konversi nama produk di consequent ke ID produk
                $consequentProductIds = [];
                foreach ($rule['consequent'] as $name) {
                    if (isset($productNameToIdMap[$name])) {
                        $consequentProductIds[] = $productNameToIdMap[$name];
                    }
                }

                // Hanya simpan aturan jika antecedent dan consequent memiliki ID produk yang valid
                if (!empty($antecedentProductIds) && !empty($consequentProductIds)) {
                    AssociationRule::create([
                        'antecedent_product_ids' => $antecedentProductIds,
                        'antecedent_names'       => $rule['antecedent'],
                        'consequent_product_ids' => $consequentProductIds,
                        'consequent_names'       => $rule['consequent'],
                        'support'                => $rule['support'],
                        'confidence'             => $rule['confidence'],
                        'lift'                   => $rule['lift'],
                    ]);
                }
            }

            DB::commit(); // Commit transaksi jika berhasil
            return response("Analisis Apriori selesai dan " . count($processedRules) . " aturan disimpan.", 200);
        } catch (\Exception | \Throwable $e) { // Tangkap Throwable juga untuk error fatal
            DB::rollBack(); // Rollback transaksi jika ada error
            \Log::error("Apriori Storage Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response("Terjadi kesalahan saat menyimpan aturan: " . $e->getMessage(), 500);
        }
    }

    /**
     * Menggunakan PHP-ML Apriori untuk menghasilkan aturan asosiasi.
     *
     * @param array $transactions Contoh: [['item1', 'item2'], ['item2', 'item3'], ...]
     * @return array Aturan asosiasi dalam format:
     * [['antecedent' => ['item1'], 'consequent' => ['item2'], 'support' => X, 'confidence' => Y, 'lift' => Z], ...]
     */
    private function getAprioriRulesFromPhpMl(array $transactions): array
    {
        // === IMPLEMENTASI APRIORI MENGGUNAKAN PHP-ML ===

        $apriori = new Apriori(
            $support = 0.1,    // Minimum Support (contoh: 1%)
            $confidence = 0.5   // Minimum Confidence (contoh: 50%)
        );

        // --- BARIS YANG DIPERBAIKI ---
        // Argumen kedua adalah array kosong untuk label, karena Apriori tidak menggunakan label.
        $apriori->train($transactions, []);
        // --- END OF FIX ---

        // Dapatkan aturan dari PHP-ML
        $phpMlRules = $apriori->getRules();

        $processedRules = [];
        $totalTransactions = count($transactions);

        foreach ($phpMlRules as $rule) {
            $antecedent = $rule['antecedent'];
            $consequent = $rule['consequent'];
            $support = $rule['support'];
            $confidence = $rule['confidence'];
            // Hitung Lift secara manual: Lift(A -> B) = Confidence(A -> B) / Support(B)
            $consequentCount = 0;
            foreach ($transactions as $transaction) {
                $allConsequentItemsPresent = true;
                foreach ($consequent as $item) {
                    if (!in_array($item, $transaction)) {
                        $allConsequentItemsPresent = false;
                        break;
                    }
                }
                if ($allConsequentItemsPresent) {
                    $consequentCount++;
                }
            }
            $supportB = ($totalTransactions > 0) ? ((float) $consequentCount / $totalTransactions) : 0.0;
            $lift = ($supportB > 0) ? ($confidence / $supportB) : 0.0;

            $processedRules[] = [
                'antecedent' => $antecedent,
                'consequent' => $consequent,
                'support'    => $support,
                'confidence' => $confidence,
                'lift'       => $lift,
            ];
        }

        return $processedRules;
    }

    /**
     * Menampilkan detail produk berdasarkan slug dan rekomendasi Apriori.
     *
     * @param Product $product (Route Model Binding akan otomatis menemukan produk berdasarkan slug)
     * @return \Illuminate\View\View
     */
    public function shows(Product $product)
    {
        $recommendedProducts = collect();

        $rulesAsAntecedent = AssociationRule::whereJsonContains('antecedent_product_ids', $product->id)
            ->orderByDesc('confidence')
            ->orderByDesc('lift')
            ->limit(5)
            ->get();

        foreach ($rulesAsAntecedent as $rule) {
            $consequentIds = $rule->consequent_product_ids;
            $productsFromRule = Product::whereIn('id', $consequentIds)
                ->where('id', '!=', $product->id)
                ->get();
            $recommendedProducts = $recommendedProducts->merge($productsFromRule);
        }

        $rulesAsConsequent = AssociationRule::whereJsonContains('consequent_product_ids', $product->id)
            ->orderByDesc('confidence')
            ->orderByDesc('lift')
            ->limit(5)
            ->get();

        foreach ($rulesAsConsequent as $rule) {
            $antecedentIds = $rule->antecedent_product_ids;
            $productsFromRule = Product::whereIn('id', $antecedentIds)
                ->where('id', '!=', $product->id)
                ->get();
            $recommendedProducts = $recommendedProducts->merge($productsFromRule);
        }

        $recommendedProducts = $recommendedProducts->unique('id')->take(4);

        return view('products.show', compact('product', 'recommendedProducts'));
    }
    /**
     * Display products by category
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Get query parameters for filters
        $request = request();
        $sortBy = $request->query('sort', 'newest');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $rating = $request->query('rating');

        $productsQuery = Product::with(['category', 'images', 'reviews'])
            ->where('category_id', $category->id)
            ->where('is_active', true);

        // Apply price filters if set
        if ($minPrice) {
            $productsQuery->where('base_price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $productsQuery->where('base_price', '<=', $maxPrice);
        }

        // Apply sorting
        switch ($sortBy) {
            case 'price_low':
                $productsQuery->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $productsQuery->orderBy('base_price', 'desc');
                break;
            case 'rating':
                $productsQuery->withAvg('reviews', 'rating')
                    ->orderByDesc('reviews_avg_rating');
                break;
            case 'newest':
            default:
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }

        $products = $productsQuery->paginate(12);

        // Get related categories (other categories except current one)
        $relatedCategories = Category::where('id', '!=', $category->id)
            ->where('is_active', true)
            ->take(5)
            ->get();

        return view('shop.category', [
            'category' => $category,
            'products' => $products,
            'subcategories' => collect([]), // Empty collection since we don't use parent_id
            'relatedCategories' => $relatedCategories,
            'categoryName' => $category->name,
            'categoryDescription' => $category->description,
            'sortBy' => $sortBy,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'rating' => $rating
        ]);
    }

    /**
     * Display a product
     */
    public function show($slug)
    {
        $product = Product::with([
            'category',
            'images',
            'options.values',
            'combinations.optionValues',
            'reviews.user'
        ])->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get the default/first combination
        $defaultCombination = $product->combinations->first();

        // If no combination exists, create a fallback default
        if ($defaultCombination === null) {
            $defaultCombination = new ProductCombination([
                'product_id' => $product->id,
                'price' => $product->base_price,
                'stock' => 100, // Default stock
                'sku' => $product->slug . '-default',
                'weight' => 1.00 // Default weight in grams
            ]);

            // Save the default combination if it doesn't exist
            if (!$product->combinations()->where('sku', $defaultCombination->sku)->exists()) {
                $defaultCombination->save();
            }
        }

        // Organize the product options and values for easier access in the view
        $productOptions = [];
        foreach ($product->options as $option) {
            $productOptions[$option->id] = [
                'name' => $option->name,
                'type' => $option->type,
                'values' => $option->values->pluck('value', 'id')->toArray()
            ];
        }

        // Map product combinations with their option values for variant selection
        $combinationsMap = [];
        foreach ($product->combinations as $combination) {
            $optionValueIds = $combination->optionValues->pluck('id')->toArray();
            sort($optionValueIds); // Ensure consistent order with JS (frontend)
            $key = implode('-', $optionValueIds);
            $combinationsMap[$key] = [
                'id' => $combination->id,
                'price' => $combination->price,
                'stock' => $combination->stock,
                'sku' => $combination->sku
            ];
        }

        // Convert combinations map to JSON for JavaScript
        $combinationsMapJson = json_encode($combinationsMap);

        // Get related products (same category)
        $relatedProducts = Product::with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        // Check if product is in any active flash sale
        $flashSaleItem = null;
        $activeFlashSale = FlashSale::with('items')
            ->where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        if ($activeFlashSale) {
            // Get all product combination IDs
            $combinationIds = $product->combinations->pluck('id')->toArray();

            // Check if any combination is in flash sale
            $flashSaleItem = $activeFlashSale->items()
                ->whereIn('product_combination_id', $combinationIds)
                ->where('is_active', true)
                ->where('stock_available', '>', 0)
                ->first();
        }

        // Calculate average rating
        $avgRating = $product->reviews->avg('rating') ?? 0;
        $totalReviews = $product->reviews->count();

        // Group ratings for statistics
        $ratingStats = [
            5 => $product->reviews->where('rating', 5)->count(),
            4 => $product->reviews->where('rating', 4)->count(),
            3 => $product->reviews->where('rating', 3)->count(),
            2 => $product->reviews->where('rating', 2)->count(),
            1 => $product->reviews->where('rating', 1)->count()
        ];

        // Convert to percentages
        foreach ($ratingStats as $rating => $count) {
            $ratingStats[$rating] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
        }

        // Add product to recently viewed
        $recentlyViewed = session()->get('recently_viewed', []);
        if (!in_array($product->id, $recentlyViewed)) {
            array_unshift($recentlyViewed, $product->id);
            $recentlyViewed = array_slice($recentlyViewed, 0, 5); // Keep only the 5 most recent
            session()->put('recently_viewed', $recentlyViewed);
        }

        $recommendedProducts = collect();

        $rulesAsAntecedent = AssociationRule::whereJsonContains('antecedent_product_ids', $product->id)
            ->orderByDesc('confidence')
            ->orderByDesc('lift')
            ->limit(5)
            ->get();

        foreach ($rulesAsAntecedent as $rule) {
            $consequentIds = $rule->consequent_product_ids;
            $productsFromRule = Product::whereIn('id', $consequentIds)
                ->where('id', '!=', $product->id)
                ->get();
            $recommendedProducts = $recommendedProducts->merge($productsFromRule);
        }

        $rulesAsConsequent = AssociationRule::whereJsonContains('consequent_product_ids', $product->id)
            ->orderByDesc('confidence')
            ->orderByDesc('lift')
            ->limit(5)
            ->get();

        foreach ($rulesAsConsequent as $rule) {
            $antecedentIds = $rule->antecedent_product_ids;
            $productsFromRule = Product::whereIn('id', $antecedentIds)
                ->where('id', '!=', $product->id)
                ->get();
            $recommendedProducts = $recommendedProducts->merge($productsFromRule);
        }

        $recommendedProducts = $recommendedProducts->unique('id')->take(4);


        return view('shop.product', [
            'product' => $product,
            'defaultCombination' => $defaultCombination,
            'productOptions' => $productOptions,
            'combinationsMapJson' => $combinationsMapJson,
            'relatedProducts' => $relatedProducts,
            'flashSaleItem' => $flashSaleItem,
            'endTime' => $activeFlashSale ? $activeFlashSale->end_time : null,
            'avgRating' => $avgRating,
            'totalReviews' => $totalReviews,
            'ratingStats' => $ratingStats,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate(12);

        return view('shop.search', [
            'products' => $products,
            'query' => $query
        ]);
    }

    /**
     * Display flash sales
     */
    public function flashSales()
    {
        $activeFlashSales = FlashSale::with(['items.productCombination.product.images'])
            ->where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->get();

        $upcomingFlashSales = FlashSale::with(['items.productCombination.product.images'])
            ->where('is_active', true)
            ->where('start_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('shop.flash-sales', [
            'activeFlashSales' => $activeFlashSales,
            'upcomingFlashSales' => $upcomingFlashSales
        ]);
    }

    /**
     * Display new arrivals
     */
    public function newArrivals()
    {
        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('shop.new-arrivals', [
            'products' => $products
        ]);
    }

    /**
     * Display best sellers
     */
    public function bestSellers()
    {
        // In a real app, this would be ordered by sales count
        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('shop.best-sellers', [
            'products' => $products
        ]);
    }

    /**
     * Get product variants via AJAX
     */
    public function getVariants(Request $request)
    {
        $productId = $request->input('product_id');
        $selectedOptions = $request->input('options', []);

        $product = Product::with(['options.values', 'combinations.optionValues'])
            ->findOrFail($productId);

        if (!$product->has_variant) {
            return response()->json([
                'success' => true,
                'has_variant' => false,
                'default_price' => $product->base_price,
                'stock' => $product->combinations()->first()->stock ?? 0
            ]);
        }

        // If options are selected, find the matching combination
        if (!empty($selectedOptions)) {
            $combination = $this->findMatchingCombination($product, $selectedOptions);

            if ($combination) {
                return response()->json([
                    'success' => true,
                    'has_variant' => true,
                    'combination_id' => $combination->id,
                    'price' => $combination->price,
                    'stock' => $combination->stock,
                    'sku' => $combination->sku
                ]);
            }
        }

        // If no options selected or no matching combination, return available options
        $availableOptions = [];
        foreach ($product->options as $option) {
            $availableOptions[$option->id] = [
                'name' => $option->name,
                'values' => $option->values->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'value' => $value->value
                    ];
                })
            ];
        }

        return response()->json([
            'success' => true,
            'has_variant' => true,
            'available_options' => $availableOptions
        ]);
    }

    /**
     * Find a matching product combination based on selected options
     */
    private function findMatchingCombination($product, $selectedOptions)
    {
        $selectedOptionValueIds = collect($selectedOptions)->values()->toArray();

        foreach ($product->combinations as $combination) {
            $combinationOptionValueIds = $combination->optionValues->pluck('id')->toArray();

            // Check if the combination has all selected option values
            $hasAllSelected = true;
            foreach ($selectedOptionValueIds as $valueId) {
                if (!in_array($valueId, $combinationOptionValueIds)) {
                    $hasAllSelected = false;
                    break;
                }
            }

            if ($hasAllSelected && count($selectedOptionValueIds) === count($combinationOptionValueIds)) {
                return $combination;
            }
        }

        return null;
    }
}