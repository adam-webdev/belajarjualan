<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        // If this is an AJAX request and user_id is provided, return addresses for that user
        if ($request->ajax() || $request->wantsJson()) {
            if ($request->has('user_id')) {
                $addresses = Address::where('user_id', $request->user_id)->get();
                return response()->json($addresses);
            }
        }

        // Normal index view
        $addresses = Address::with('user')->latest()->get();
        return view('admin.addresses.index', compact('addresses'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.addresses.create', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'recipient_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'province' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'address_detail' => 'required|string',
                'is_default' => 'boolean',
            ]);

            // Process is_default checkbox
            $validated['is_default'] = $request->has('is_default');

            // If this is the first address for the user, make it default
            $existingAddresses = Address::where('user_id', $validated['user_id'])->count();
            if ($existingAddresses === 0) {
                $validated['is_default'] = true;
            }

            // If this address is being set as default, remove default from all other addresses
            if ($validated['is_default']) {
                Address::where('user_id', $validated['user_id'])
                    ->update(['is_default' => false]);
            }

            $address = Address::create($validated);

            // If this is an AJAX request (from order creation form)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Address created successfully',
                    'address' => $address
                ]);
            }

            return redirect()->route('admin.addresses.index')
                ->with('success', 'Address created successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create address: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->withInput()
                ->with('error', 'Failed to create address: ' . $e->getMessage());
        }
    }

    public function show(Address $address)
    {
        $address->load('user');
        return view('admin.addresses.show', compact('address'));
    }

    public function edit(Address $address)
    {
        $users = User::all();
        return view('admin.addresses.edit', compact('address', 'users'));
    }

    public function update(Request $request, Address $address)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address_detail' => 'required|string',
            'is_default' => 'boolean',
        ]);

        // Process is_default checkbox
        $validated['is_default'] = $request->has('is_default');

        if ($validated['is_default']) {
            Address::where('user_id', $validated['user_id'])
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('admin.addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        if ($address->orders()->exists()) {
            return redirect()->route('admin.addresses.index')
                ->with('error', 'Cannot delete address with existing orders.');
        }

        $address->delete();

        return redirect()->route('admin.addresses.index')
            ->with('success', 'Address deleted successfully.');
    }

    public function setDefault(Address $address)
    {
        $address->setAsDefault();

        return redirect()->route('admin.addresses.index')
            ->with('success', 'Default address updated successfully.');
    }

    public function userAddresses(User $user)
    {
        $addresses = $user->addresses()->latest()->get();
        return view('admin.addresses.user-addresses', compact('user', 'addresses'));
    }
}