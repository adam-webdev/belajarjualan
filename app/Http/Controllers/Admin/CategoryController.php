<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        $trashedCount = Category::onlyTrashed()->count();
        return view('admin.categories.index', compact('categories', 'trashedCount'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Process is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load(['products', 'products.images']);
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Process is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category, Request $request)
    {
        $forceDelete = $request->has('force_delete');

        if ($category->products()->exists() && !$forceDelete) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with existing products. Use force delete option to remove category and set related products to no category.');
        }

        // If force delete, update products to have no category
        if ($forceDelete && $category->products()->exists()) {
            $category->products()->update(['category_id' => null]);
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function batchDelete(Request $request)
    {
        $categoryIds = $request->input('categories', []);
        $forceDelete = $request->has('force_delete');

        if (empty($categoryIds)) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'No categories selected for deletion.');
        }

        $categories = Category::whereIn('id', $categoryIds)->get();
        $productsExist = false;

        // Check if any selected categories have products
        foreach ($categories as $category) {
            if ($category->products()->exists()) {
                $productsExist = true;
                break;
            }
        }

        // If categories have products and not force deleting, return with error
        if ($productsExist && !$forceDelete) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Some categories have products. Use force delete option to remove categories and set related products to no category.');
        }

        // Process deletion
        foreach ($categories as $category) {
            // If force delete, update products to have no category
            if ($forceDelete && $category->products()->exists()) {
                $category->products()->update(['category_id' => null]);
            }

            // Delete category image if it exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();
        }

        $count = count($categories);
        return redirect()->route('admin.categories.index')
            ->with('success', "{$count} categories deleted successfully.");
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category status updated successfully.');
    }

    public function products(Category $category)
    {
        $products = $category->products()->with(['images'])->latest()->get();
        return view('admin.categories.products', compact('category', 'products'));
    }

    /**
     * Display a listing of trashed categories.
     */
    public function trash()
    {
        $trashedCategories = Category::onlyTrashed()->latest()->get();
        return view('admin.categories.trash', compact('trashedCategories'));
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('admin.categories.trash')
            ->with('success', 'Category restored successfully.');
    }

    /**
     * Permanently delete a soft-deleted category.
     */
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // If category has products, set their category_id to null
        if ($category->products()->exists()) {
            $category->products()->update(['category_id' => null]);
        }

        $category->forceDelete();

        return redirect()->route('admin.categories.trash')
            ->with('success', 'Category permanently deleted.');
    }

    /**
     * Batch restore categories from trash.
     */
    public function batchRestore(Request $request)
    {
        $categoryIds = $request->input('categories', []);

        if (empty($categoryIds)) {
            return redirect()->route('admin.categories.trash')
                ->with('error', 'No categories selected for restoration.');
        }

        Category::onlyTrashed()->whereIn('id', $categoryIds)->restore();

        $count = count($categoryIds);
        return redirect()->route('admin.categories.trash')
            ->with('success', "{$count} categories restored successfully.");
    }

    /**
     * Batch force delete categories from trash.
     */
    public function batchForceDelete(Request $request)
    {
        $categoryIds = $request->input('categories', []);

        if (empty($categoryIds)) {
            return redirect()->route('admin.categories.trash')
                ->with('error', 'No categories selected for deletion.');
        }

        $categories = Category::onlyTrashed()->whereIn('id', $categoryIds)->get();

        foreach ($categories as $category) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            // If category has products, set their category_id to null
            if ($category->products()->exists()) {
                $category->products()->update(['category_id' => null]);
            }

            $category->forceDelete();
        }

        $count = count($categories);
        return redirect()->route('admin.categories.trash')
            ->with('success', "{$count} categories permanently deleted.");
    }
}