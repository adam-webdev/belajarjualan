<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductCombination;
use App\Models\ProductCombinationValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $messages = [
                'category_id.required' => 'Kategori produk harus dipilih',
                'category_id.exists' => 'Kategori yang dipilih tidak valid',
                'name.required' => 'Nama produk harus diisi',
                'name.string' => 'Nama produk harus berupa teks',
                'name.max' => 'Nama produk maksimal 255 karakter',
                'description.required' => 'Deskripsi produk harus diisi',
                'description.string' => 'Deskripsi produk harus berupa teks',
                'base_price.required' => 'Harga dasar produk harus diisi',
                'base_price.numeric' => 'Harga dasar produk harus berupa angka',
                'base_price.min' => 'Harga dasar produk minimal 0',
                'has_variant.boolean' => 'Status varian produk tidak valid',
                'is_active.boolean' => 'Status aktif produk tidak valid',
                'images.*.image' => 'File yang diunggah harus berupa gambar',
                'images.*.mimes' => 'Format gambar harus jpeg, png, jpg, gif, webp, atau jfif',
                'images.*.max' => 'Ukuran gambar maksimal 4MB'
            ];

            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'base_price' => 'required|numeric|min:0',
                'has_variant' => 'sometimes|boolean',
                'is_active' => 'sometimes|boolean',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,jfif|max:4096',
            ], $messages);

            // Format price properly
            $priceInput = str_replace(',', '.', $request->input('base_price'));
            $validated['base_price'] = number_format((float) $priceInput, 2, '.', '');

            // Generate unique slug
            $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);

            // Handle boolean fields properly
            $validated['has_variant'] = $request->has('has_variant');
            $validated['is_active'] = $request->has('is_active');

            DB::beginTransaction();

            // Create product
            $product = Product::create($validated);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    try {
                        $path = $image->store('products', 'public');

                        $product->images()->create([
                            'image_path' => $path,
                            'is_primary' => $index === 0,  // First image is primary
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to upload image: ' . $e->getMessage());
                        throw new \Exception('Gagal mengunggah satu atau lebih gambar: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();

            // Log success
            \Log::info('Product created successfully', [
                'product_id' => $product->id,
                'name' => $product->name,
                'user_id' => auth()->id()
            ]);

            return redirect()
                ->route('admin.products.show', $product)
                ->with('success', 'Produk berhasil dibuat.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Product validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except('images')
            ]);
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create product: ' . $e->getMessage(), [
                'exception' => $e,
                'input' => $request->except('images')
            ]);
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat produk: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'options.values', 'combinations.values.optionValue.option']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load(['category', 'images', 'options.values', 'combinations.values.optionValue.option']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $messages = [
            'category_id.required' => 'Kategori produk harus dipilih',
            'category_id.exists' => 'Kategori yang dipilih tidak valid',
            'name.required' => 'Nama produk harus diisi',
            'name.string' => 'Nama produk harus berupa teks',
            'name.max' => 'Nama produk maksimal 255 karakter',
            'description.required' => 'Deskripsi produk harus diisi',
            'description.string' => 'Deskripsi produk harus berupa teks',
            'base_price.required' => 'Harga dasar produk harus diisi',
            'base_price.numeric' => 'Harga dasar produk harus berupa angka',
            'base_price.min' => 'Harga dasar produk minimal 0',
            'is_active.boolean' => 'Status aktif produk tidak valid',
            'has_variant.boolean' => 'Status varian produk tidak valid'
        ];

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
            'has_variant' => 'sometimes|boolean',
        ], $messages);

        DB::beginTransaction();
        try {
            // Build data array for update
            $data = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'base_price' => $request->base_price,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'has_variant' => $request->has('has_variant') ? 1 : 0,
            ];

            // Jika produk memiliki opsi, pastikan has_variant tetap 1
            if ($product->options()->exists()) {
                $data['has_variant'] = 1;
            }

            // Update the product
            $product->update($data);

            DB::commit();
            return redirect()
                ->route('admin.products.show', $product)
                ->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        // Delete all related images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete all related options and combinations
        $product->options()->delete();
        $product->combinations()->delete();

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    // Image management
    public function addImages(Request $request, Product $product)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp,jfif|max:4096',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $isPrimary = !$product->images()->exists();

                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => $isPrimary,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Images added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add images: ' . $e->getMessage());
        }
    }

    public function deleteImage(ProductImage $image)
    {
        $product = $image->product;
        $isPrimary = $image->is_primary;

        // Delete the file from storage
        Storage::disk('public')->delete($image->image_path);

        // Delete the database record
        $image->delete();

        // If primary image was deleted, set another image as primary
        if ($isPrimary) {
            $newPrimary = $product->images()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function setPrimaryImage(ProductImage $image)
    {
        $product = $image->product;

        // Set all other images as not primary
        $product->images()->where('id', '!=', $image->id)->update(['is_primary' => false]);

        // Set this image as primary
        $image->update(['is_primary' => true]);

        return redirect()->back()->with('success', 'Primary image set successfully.');
    }

    // Product Variants Management
    public function addOption(Request $request, Product $product)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255',
            'option_values' => 'required|array|min:1',
            'option_values.*' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Set product as having variants
            if (!$product->has_variant) {
                $product->update(['has_variant' => true]);
            }

            // Create option
            $option = $product->options()->create([
                'name' => $validated['option_name'],
            ]);

            // Create option values
            foreach ($validated['option_values'] as $value) {
                $option->values()->create([
                    'value' => $value,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Option added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add option: ' . $e->getMessage());
        }
    }

    public function updateOption(Request $request, ProductOption $option)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255',
            'option_values' => 'required|array|min:1',
            'option_values.*' => 'required|string|max:255',
            'existing_values' => 'array',
            'existing_values.*' => 'string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update option name
            $option->update(['name' => $validated['option_name']]);

            // Update existing values
            if (!empty($validated['existing_values'])) {
                foreach ($validated['existing_values'] as $valueId => $value) {
                    ProductOptionValue::where('id', $valueId)->update(['value' => $value]);
                }
            }

            // Add new values
            foreach ($validated['option_values'] as $value) {
                $option->values()->create([
                    'value' => $value,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Option updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update option: ' . $e->getMessage());
        }
    }

    public function deleteOption(ProductOption $option)
    {
        $product = $option->product;

        // Check if the option is used in any combinations
        $usedInCombinations = ProductCombinationValue::whereHas('optionValue', function($query) use ($option) {
            $query->where('product_option_id', $option->id);
        })->exists();

        if ($usedInCombinations) {
            return redirect()->back()->with('error', 'Cannot delete option that is used in product combinations.');
        }

        $option->delete();

        // If no options left, set product as not having variants
        if (!$product->options()->exists()) {
            $product->update(['has_variant' => false]);
        }

        return redirect()->back()->with('success', 'Option deleted successfully.');
    }

    public function editOption(ProductOption $option)
    {
        $option->load(['values', 'product']);
        return view('admin.products.option-edit', compact('option'));
    }

    public function addOptionValue(Request $request, ProductOption $option)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $option->values()->create([
            'value' => $validated['value'],
        ]);

        return redirect()->back()->with('success', 'Option value added successfully.');
    }

    public function deleteOptionValue($id)
    {
        $optionValue = ProductOptionValue::findOrFail($id);
        $option = $optionValue->option;

        // Check if the option value is used in any combinations
        $usedInCombinations = ProductCombinationValue::where('product_option_value_id', $id)->exists();

        if ($usedInCombinations) {
            return redirect()->back()->with('error', 'Cannot delete option value that is used in product combinations.');
        }

        $optionValue->delete();

        // Check if there are any values left
        if ($option->values()->count() == 0) {
            return redirect()->route('admin.products.show', $option->product)->with('warning', 'Last option value was deleted. Consider adding new values or deleting the option.');
        }

        return redirect()->back()->with('success', 'Option value deleted successfully.');
    }

    public function manageCombinations(Product $product)
    {
        $product->load(['options.values', 'combinations.values.optionValue.option']);
        return view('admin.products.combinations-manage', compact('product'));
    }

    public function generateCombinations(Request $request, Product $product)
    {
        $validated = $request->validate([
            'options' => 'required|array',
            'options.*' => 'required|exists:product_options,id',
            'base_sku' => 'required|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'base_stock' => 'required|integer|min:0',
            'base_weight' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $options = ProductOption::with('values')
                ->whereIn('id', $validated['options'])
                ->get();

            // Get all possible combinations of option values
            $valueSets = $options->map(function ($option) {
                return $option->values->pluck('id')->toArray();
            })->toArray();

            // Generate Cartesian product of all option values
            $combinations = $this->generateCartesianProduct($valueSets);

            // Create product combinations
            $counter = 1;
            foreach ($combinations as $combinationValues) {
                // Check if this combination already exists
                $existingCombination = $this->findExistingCombination($product, $combinationValues);
                if ($existingCombination) {
                    continue;
                }

                // Create a new combination
                $sku = $validated['base_sku'] . '-' . $counter;
                $counter++;

                $combination = $product->combinations()->create([
                    'sku' => $sku,
                    'price' => $validated['base_price'],
                    'stock' => $validated['base_stock'],
                    'weight' => $validated['base_weight'],
                ]);

                // Add option values to combination
                foreach ($combinationValues as $optionValueId) {
                    $combination->values()->create([
                        'product_option_value_id' => $optionValueId,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Product combinations generated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to generate combinations: ' . $e->getMessage());
        }
    }

    // Helper method to generate Cartesian product
    private function generateCartesianProduct(array $arrays)
    {
        $result = [[]];
        foreach ($arrays as $array) {
            $append = [];
            foreach ($result as $product) {
                foreach ($array as $item) {
                    $product[] = $item;
                    $append[] = $product;
                }
            }
            $result = $append;
        }
        return $result;
    }

    // Helper method to find existing combination
    private function findExistingCombination(Product $product, array $optionValueIds)
    {
        // Sort the option value IDs to ensure consistent comparison
        sort($optionValueIds);

        foreach ($product->combinations as $combination) {
            $combinationOptionValueIds = $combination->values->pluck('product_option_value_id')->toArray();
            sort($combinationOptionValueIds);

            if ($optionValueIds == $combinationOptionValueIds) {
                return $combination;
            }
        }

        return null;
    }

    public function addCombination(Request $request, Product $product)
    {
        $validated = $request->validate([
            'option_values' => 'required|array|min:1',
            'option_values.*' => 'required|exists:product_option_values,id',
            'sku' => 'required|string|max:255|unique:product_combinations,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Create combination
            $combination = $product->combinations()->create([
                'sku' => $validated['sku'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'weight' => $validated['weight'],
            ]);

            // Add option values to combination
            foreach ($validated['option_values'] as $optionValueId) {
                $combination->values()->create([
                    'product_option_value_id' => $optionValueId,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Combination added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add combination: ' . $e->getMessage());
        }
    }

    public function updateCombination(Request $request, ProductCombination $combination)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:product_combinations,sku,' . $combination->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
        ]);

        $combination->update($validated);

        return redirect()->back()->with('success', 'Combination updated successfully.');
    }

    public function deleteCombination(ProductCombination $combination)
    {
        // Check if combination is used in orders
        if ($combination->orderDetails()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete combination that is used in orders.');
        }

        $combination->delete();

        return redirect()->back()->with('success', 'Combination deleted successfully.');
    }

    /**
     * Get product combinations for API
     */
    public function getCombinations(Product $product, Request $request)
    {
        try {
            $combinations = $product->combinations()
                ->with(['values.optionValue.option'])
                ->get();

            return response()->json($combinations);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading combinations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product data for API
     */
    public function getProductData(Product $product, Request $request)
    {
        try {
            $product->load('category', 'images');
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading product data: ' . $e->getMessage()
            ], 500);
        }
    }
}