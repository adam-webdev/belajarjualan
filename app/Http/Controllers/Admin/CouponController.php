<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        return view('admin.coupons.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:0',
            'max_uses_per_user' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        // Process checkbox
        $validated['is_active'] = $request->has('is_active');

        // Format dates if provided
        if (!empty($validated['starts_at'])) {
            $validated['starts_at'] = Carbon::parse($validated['starts_at']);
        }

        if (!empty($validated['expires_at'])) {
            $validated['expires_at'] = Carbon::parse($validated['expires_at']);
        }

        // Initialize used_count
        $validated['used_count'] = 0;

        $coupon = Coupon::create($validated);

        // Attach categories if any
        if ($request->has('categories')) {
            $coupon->categories()->attach($request->categories);
        }

        // Attach products if any
        if ($request->has('products')) {
            $coupon->products()->attach($request->products);
        }

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['categories', 'products', 'usages.user']);
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $coupon->load(['categories', 'products']);

        return view('admin.coupons.edit', compact('coupon', 'categories', 'products'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:0',
            'max_uses_per_user' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        // Process checkbox
        $validated['is_active'] = $request->has('is_active');

        // Format dates if provided
        if (!empty($validated['starts_at'])) {
            $validated['starts_at'] = Carbon::parse($validated['starts_at']);
        }

        if (!empty($validated['expires_at'])) {
            $validated['expires_at'] = Carbon::parse($validated['expires_at']);
        }

        $coupon->update($validated);

        // Sync categories
        if ($request->has('categories')) {
            $coupon->categories()->sync($request->categories);
        } else {
            $coupon->categories()->detach();
        }

        // Sync products
        if ($request->has('products')) {
            $coupon->products()->sync($request->products);
        } else {
            $coupon->products()->detach();
        }

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        // Check if the coupon is used in any order
        if ($coupon->orders()->exists()) {
            return redirect()->route('admin.coupons.index')
                ->with('error', 'Cannot delete coupon that is used in orders.');
        }

        // Check if the coupon is used in any payment
        if ($coupon->payments()->exists()) {
            return redirect()->route('admin.coupons.index')
                ->with('error', 'Cannot delete coupon that is used in payments.');
        }

        // Detach all relationships
        $coupon->categories()->detach();
        $coupon->products()->detach();

        // Delete usage records
        $coupon->usages()->delete();

        // Delete the coupon
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon status updated successfully.');
    }

    public function generateCode()
    {
        $code = strtoupper(Str::random(8));

        // Ensure code is unique
        while (Coupon::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(8));
        }

        return response()->json(['code' => $code]);
    }
}