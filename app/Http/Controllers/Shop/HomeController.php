<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\AssociationRule;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashSale;

class HomeController extends Controller
{
    /**
     * Display the shop home page
     */
    public function index()
    {
        // Get featured categories
        $categories = Category::where('is_active', true)
            ->take(6)
            ->get();

        // Get featured products
        $featuredProducts = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Get active flash sales with their items
        $activeFlashSale = FlashSale::with(['items.productCombination.product', 'items.productCombination.optionValues'])
            ->where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        // Get best selling products
        $bestSellingProducts = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc') // In a real app, this would be ordered by sales count
            ->take(4)
            ->get();



        // Ambil semua produk (untuk bagian lain di homepage Anda, jika ada)

        // --- Logika untuk Rekomendasi Apriori Global ---
        $aprioriHomepageRecommendations = collect();

        // Mengambil 10 aturan asosiasi teratas berdasarkan lift atau confidence
        // Anda bisa menyesuaikan jumlah limit dan order by nya
        $topRules = AssociationRule::orderByDesc('lift') // Prioritaskan aturan dengan Lift tertinggi
            ->orderByDesc('confidence') // Jika Lift sama, prioritaskan confidence
            ->limit(10) // Ambil lebih banyak aturan untuk mendapatkan lebih banyak produk unik
            ->get();

        $collectedProductIds = [];

        foreach ($topRules as $rule) {
            // Ambil semua produk dari kedua sisi aturan (antecedent dan consequent)
            // karena keduanya adalah produk yang "sering bersama".
            $productIdsInRule = array_merge($rule->antecedent_product_ids, $rule->consequent_product_ids);

            foreach ($productIdsInRule as $productId) {
                // Pastikan ID produk belum ada di koleksi rekomendasi dan batas belum tercapai
                if (!in_array($productId, $collectedProductIds) && $aprioriHomepageRecommendations->count() < 6) { // Batasi hingga 6 produk
                    $product = Product::find($productId);
                    if ($product) {
                        $aprioriHomepageRecommendations->push($product);
                        $collectedProductIds[] = $productId; // Tambahkan ke daftar ID yang sudah terkumpul
                    }
                }
            }
            if ($aprioriHomepageRecommendations->count() >= 6) { // Hentikan jika sudah cukup
                break;
            }
        }


        return view('shop.home', compact(
            'categories',
            'featuredProducts',
            'activeFlashSale',
            'bestSellingProducts',
            'aprioriHomepageRecommendations',
        ));
    }
}