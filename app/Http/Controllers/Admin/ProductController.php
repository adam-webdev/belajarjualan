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
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'has_variant' => 'boolean',
            'is_active' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['has_variant'] = $request->has('has_variant');
        $validated['is_active'] = $request->has('is_active');

        // Ensure base_price is properly formatted as decimal
        $priceInput = str_replace(',', '.', $request->input('base_price'));
        $validated['base_price'] = number_format((float) $priceInput, 2, '.', '');

        DB::beginTransaction();
        try {
            $product = Product::create($validated);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');

                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => $index === 0,  // First image is primary
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.show', $product)
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
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
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'base_price' => 'required|numeric|min:0',
                'is_active' => 'boolean',
            ]);

            // Process boolean fields
            $validated['is_active'] = $request->has('is_active') ? true : false;

            // Don't change has_variant if product already has variants
            if (!$product->options()->exists()) {
                $validated['has_variant'] = $request->has('has_variant') ? true : false;
            }

            // Set base_price directly from the validated input
            // No need for additional formatting as it's already validated as numeric
            $validated['base_price'] = (float)$request->input('base_price');

            $product->update($validated);

            return redirect()->route('admin.products.show', $product)
                ->with('success', 'Product updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()
                ->with('error', 'Please fix the errors in the form.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        // Check if product has orders
        if ($product->orderDetails()->exists()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Cannot delete product with existing orders.');
        }

        // Delete all related images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    // Image management
    public function addImages(Request $request, Product $product)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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
}