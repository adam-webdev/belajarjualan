<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(['admin', 'user'])]
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['addresses', 'orders', 'wishlists', 'reviews']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(['admin', 'user'])]
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed'
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->orders()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete user with existing orders.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleRole(User $user)
    {
        $user->update([
            'role' => $user->role === 'admin' ? 'user' : 'admin'
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User role updated successfully.');
    }

    public function addresses(User $user)
    {
        $addresses = $user->addresses()->latest()->get();
        return view('admin.users.addresses', compact('user', 'addresses'));
    }

    public function orders(User $user)
    {
        $orders = $user->orders()->with(['items', 'payment'])->latest()->get();
        return view('admin.users.orders', compact('user', 'orders'));
    }

    public function wishlist(User $user)
    {
        $wishlist = $user->wishlists()->with(['productCombination', 'productCombination.product'])->latest()->get();
        return view('admin.users.wishlist', compact('user', 'wishlist'));
    }

    public function reviews(User $user)
    {
        $reviews = $user->reviews()->with('product')->latest()->get();
        return view('admin.users.reviews', compact('user', 'reviews'));
    }
}