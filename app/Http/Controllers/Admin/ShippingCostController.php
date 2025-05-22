<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCost;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingCostController extends Controller
{
    public function index()
    {
        $shippingCosts = ShippingCost::with('shippingMethod')->latest()->get();
        return view('admin.shipping.costs.index', compact('shippingCosts'));
    }

    public function create()
    {
        $shippingMethods = ShippingMethod::where('is_active', true)->get();
        return view('admin.shipping.costs.create', compact('shippingMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
        ]);

        // Check if the shipping cost already exists for this method and location
        $exists = ShippingCost::where('shipping_method_id', $validated['shipping_method_id'])
            ->where('province', $validated['province'])
            ->where('city', $validated['city'])
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()
                ->with('error', 'Shipping cost for this method and location already exists.');
        }

        ShippingCost::create($validated);

        return redirect()->route('admin.shipping.costs.index')
            ->with('success', 'Shipping cost created successfully.');
    }

    public function show(ShippingCost $cost)
    {
        $cost->load('shippingMethod');
        return view('admin.shipping.costs.show', compact('cost'));
    }

    public function edit(ShippingCost $cost)
    {
        $shippingMethods = ShippingMethod::where('is_active', true)->get();
        return view('admin.shipping.costs.edit', compact('cost', 'shippingMethods'));
    }

    public function update(Request $request, ShippingCost $cost)
    {
        $validated = $request->validate([
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
        ]);

        // Check if the shipping cost already exists for this method and location (excluding this one)
        $exists = ShippingCost::where('shipping_method_id', $validated['shipping_method_id'])
            ->where('province', $validated['province'])
            ->where('city', $validated['city'])
            ->where('id', '!=', $cost->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()
                ->with('error', 'Shipping cost for this method and location already exists.');
        }

        $cost->update($validated);

        return redirect()->route('admin.shipping.costs.index')
            ->with('success', 'Shipping cost updated successfully.');
    }

    public function destroy(ShippingCost $cost)
    {
        $cost->delete();

        return redirect()->route('admin.shipping.costs.index')
            ->with('success', 'Shipping cost deleted successfully.');
    }

    public function byMethod(ShippingMethod $method)
    {
        $shippingCosts = $method->shippingCosts()->latest()->get();
        return view('admin.shipping.costs.by-method', compact('method', 'shippingCosts'));
    }

    // Add a new method to calculate shipping cost for an order
    public function calculateCost(Request $request)
    {
        try {
            $request->validate([
                'method_id' => 'required|exists:shipping_methods,id',
                'address_id' => 'required|exists:addresses,id',
            ]);

            $methodId = $request->method_id;
            $address = \App\Models\Address::findOrFail($request->address_id);

            // Get shipping cost based on location
            $cost = \App\Models\ShippingCost::where('shipping_method_id', $methodId)
                ->where(function ($query) use ($address) {
                    $query->where('city', $address->city)
                          ->orWhere('province', $address->province ?? $address->state)
                          ->orWhere('district', $address->district);
                })
                ->first();

            if ($cost) {
                return response()->json([
                    'success' => true,
                    'cost' => $cost->cost,
                    'method_name' => $cost->shippingMethod->name ?? 'Shipping'
                ]);
            }

            // If no specific cost found, get default cost for the method
            $method = \App\Models\ShippingMethod::findOrFail($methodId);

            return response()->json([
                'success' => true,
                'cost' => $method->default_cost,
                'method_name' => $method->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating shipping cost: ' . $e->getMessage()
            ], 500);
        }
    }
}