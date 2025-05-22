<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use App\Models\ProductCombination;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::latest()->get();
        return view('admin.flash-sales.index', compact('flashSales'));
    }

    public function create()
    {
        $products = Product::with(['combinations'])->where('is_active', true)->get();
        return view('admin.flash-sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Process checkbox
        $validated['is_active'] = $request->has('is_active');

        // Format dates
        $validated['start_time'] = Carbon::parse($validated['start_time']);
        $validated['end_time'] = Carbon::parse($validated['end_time']);

        DB::beginTransaction();
        try {
            $flashSale = FlashSale::create($validated);
            DB::commit();

            return redirect()->route('admin.flash-sales.index')
                ->with('success', 'Flash sale created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create flash sale: ' . $e->getMessage());
        }
    }

    public function show(FlashSale $flashSale)
    {
        // Load all nested relationships needed for the view
        $flashSale->load([
            'items.productCombination.product.images',
            'items.productCombination.combinationValues.optionValue.option'
        ]);

        // Also load products to be used in the add items form
        $products = Product::with([
            'combinations.combinationValues.optionValue.option',
            'images'
        ])
        ->where('is_active', true)
        ->get();

        return view('admin.flash-sales.show', compact('flashSale', 'products'));
    }

    public function edit(FlashSale $flashSale)
    {
        $products = Product::with(['combinations'])->where('is_active', true)->get();
        $flashSale->load('items.productCombination.product');

        return view('admin.flash-sales.edit', compact('flashSale', 'products'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Process checkbox
        $validated['is_active'] = $request->has('is_active');

        // Format dates
        $validated['start_time'] = Carbon::parse($validated['start_time']);
        $validated['end_time'] = Carbon::parse($validated['end_time']);

        DB::beginTransaction();
        try {
            $flashSale->update($validated);
            DB::commit();

            return redirect()->route('admin.flash-sales.index')
                ->with('success', 'Flash sale updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update flash sale: ' . $e->getMessage());
        }
    }

    public function destroy(FlashSale $flashSale)
    {
        // Check if the flash sale has any items with sales
        $hasSales = $flashSale->items()->where('stock_sold', '>', 0)->exists();

        if ($hasSales) {
            return redirect()->route('admin.flash-sales.index')
                ->with('error', 'Cannot delete flash sale with existing sales.');
        }

        // Delete items first, then delete the flash sale
        $flashSale->items()->delete();
        $flashSale->delete();

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale deleted successfully.');
    }

    public function toggleStatus(FlashSale $flashSale)
    {
        $flashSale->update(['is_active' => !$flashSale->is_active]);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale status updated successfully.');
    }

    public function addItems(Request $request, FlashSale $flashSale)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_combination_id' => 'required|exists:product_combinations,id',
            'items.*.discount_price' => 'required|numeric|min:0',
            'items.*.stock_available' => 'required|integer|min:1',
            'items.*.purchase_limit' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $item) {
                // Check if combination already exists in this flash sale
                $exists = $flashSale->items()
                    ->where('product_combination_id', $item['product_combination_id'])
                    ->exists();

                if (!$exists) {
                    $flashSale->items()->create([
                        'product_combination_id' => $item['product_combination_id'],
                        'discount_price' => $item['discount_price'],
                        'stock_available' => $item['stock_available'],
                        'purchase_limit' => $item['purchase_limit'] ?? null,
                        'stock_sold' => 0,
                        'is_active' => true,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.flash-sales.show', $flashSale)
                ->with('success', 'Flash sale items added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Failed to add flash sale items: ' . $e->getMessage());
        }
    }

    public function removeItem(FlashSaleItem $item)
    {
        $flashSale = $item->flashSale;

        // Check if the item has sales
        if ($item->stock_sold > 0) {
            return redirect()->route('admin.flash-sales.show', $flashSale)
                ->with('error', 'Cannot remove item with existing sales.');
        }

        $item->delete();

        return redirect()->route('admin.flash-sales.show', $flashSale)
            ->with('success', 'Flash sale item removed successfully.');
    }

    public function updateItem(Request $request, FlashSaleItem $item)
    {
        $validated = $request->validate([
            'discount_price' => 'required|numeric|min:0',
            'stock_available' => 'required|integer|min:' . $item->stock_sold,
            'purchase_limit' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $item->update($validated);

        return redirect()->route('admin.flash-sales.show', $item->flashSale)
            ->with('success', 'Flash sale item updated successfully.');
    }
}