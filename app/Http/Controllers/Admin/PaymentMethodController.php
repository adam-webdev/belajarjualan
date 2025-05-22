<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of payment methods.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->get();
        return view('admin.payments.methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create()
    {
        return view('admin.payments.methods.create');
    }

    /**
     * Store a newly created payment method.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank_transfer,e_wallet',
            'description' => 'nullable|string',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'is_active' => 'boolean'
        ]);

        // Generate code from name
        $validated['code'] = Str::slug($validated['name']);

        // Store config
        $validated['config'] = [
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number']
        ];

        // Remove account fields from validated data
        unset($validated['account_name']);
        unset($validated['account_number']);

        // Process is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        PaymentMethod::create($validated);

        return redirect()->route('admin.payments.methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $method)
    {
        return view('admin.payments.methods.edit', compact('method'));
    }

    /**
     * Update the specified payment method.
     */
    public function update(Request $request, PaymentMethod $method)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank_transfer,e_wallet',
            'description' => 'nullable|string',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'is_active' => 'boolean'
        ]);

        // Generate code from name
        $validated['code'] = Str::slug($validated['name']);

        // Store config
        $validated['config'] = [
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number']
        ];

        // Remove account fields from validated data
        unset($validated['account_name']);
        unset($validated['account_number']);

        // Process is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        $method->update($validated);

        return redirect()->route('admin.payments.methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    /**
     * Remove the specified payment method.
     */
    public function destroy(PaymentMethod $method)
    {
        // Check if payment method is being used
        if ($method->payments()->exists()) {
            return redirect()->route('admin.payments.methods.index')
                ->with('error', 'Cannot delete payment method that is being used.');
        }

        $method->delete();

        return redirect()->route('admin.payments.methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }

    /**
     * Toggle payment method status.
     */
    public function toggleStatus(PaymentMethod $method)
    {
        $method->update(['is_active' => !$method->is_active]);
        return redirect()->route('admin.payments.methods.index')
            ->with('success', 'Payment method status toggled successfully.');
    }
}