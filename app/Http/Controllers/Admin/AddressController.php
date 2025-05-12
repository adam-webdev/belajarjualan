<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
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
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string',
            'is_default' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        if ($validated['is_default']) {
            Address::where('user_id', $validated['user_id'])
                ->update(['is_default' => false]);
        }

        Address::create($validated);

        return redirect()->route('admin.addresses.index')
            ->with('success', 'Address created successfully.');
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string',
            'is_default' => 'boolean',
            'notes' => 'nullable|string'
        ]);

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