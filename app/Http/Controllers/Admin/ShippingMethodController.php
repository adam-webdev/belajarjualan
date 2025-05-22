<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $shippingMethods = ShippingMethod::latest()->get();
        return view('admin.shipping.methods.index', compact('shippingMethods'));
    }

    public function create()
    {
        return view('admin.shipping.methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_methods,code',
            'default_cost' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Process is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        // Set default cost if provided
        $validated['default_cost'] = $validated['default_cost'] ?? 0;

        ShippingMethod::create($validated);

        return redirect()->route('admin.shipping.methods.index')
            ->with('success', 'Shipping method created successfully.');
    }

    public function show(ShippingMethod $method)
    {
        $method->load('shippingCosts');
        return view('admin.shipping.methods.show', compact('method'));
    }

    public function edit(ShippingMethod $method)
    {
        return view('admin.shipping.methods.edit', compact('method'));
    }

    public function update(Request $request, ShippingMethod $method)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_methods,code,' . $method->id,
            'default_cost' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Process is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        // Set default cost if provided
        $validated['default_cost'] = $validated['default_cost'] ?? $method->default_cost ?? 0;

        $method->update($validated);

        return redirect()->route('admin.shipping.methods.index')
            ->with('success', 'Shipping method updated successfully.');
    }

    public function destroy(ShippingMethod $method)
    {
        // Check if the shipping method has related shipping costs
        if ($method->shippingCosts()->exists()) {
            return redirect()->route('admin.shipping.methods.index')
                ->with('error', 'Cannot delete shipping method with existing shipping costs.');
        }

        $method->delete();

        return redirect()->route('admin.shipping.methods.index')
            ->with('success', 'Shipping method deleted successfully.');
    }

    public function toggleStatus(ShippingMethod $method)
    {
        $method->update(['is_active' => !$method->is_active]);
        return redirect()->route('admin.shipping.methods.index')
            ->with('success', 'Shipping method status toggled successfully.');
    }

    /**
     * Get all active shipping methods as JSON
     */
    public function getAllMethods(Request $request)
    {
        try {
            $methods = ShippingMethod::where('is_active', true)
                ->select('id', 'name', 'code', 'default_cost')
                ->get();

            if ($request->expectsJson()) {
                return response()->json($methods);
            }

            return response()->json([
                'success' => true,
                'data' => $methods
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading shipping methods: ' . $e->getMessage()
            ], 500);
        }
    }
}