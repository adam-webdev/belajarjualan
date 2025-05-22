<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display user profile page
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        $orders = $user->orders()
            ->with(['details.productCombination.product'])
            ->latest()
            ->paginate(10);
        $wishlist = $user->wishlist()
            ->with(['productCombination.product.images'])
            ->paginate(12);

        return view('shop.profile', compact('user', 'addresses', 'orders', 'wishlist'));
    }

    /**
     * Update user profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Store new address
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'address_detail' => 'required|string',
            'is_default' => 'nullable|boolean'
        ]);

        // Set is_default value based on checkbox
        $validated['is_default'] = $request->has('is_default');

        // If this is the first address or marked as default, set others to non-default
        if ($validated['is_default'] || Auth::user()->addresses()->count() === 0) {
            Auth::user()->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        Auth::user()->addresses()->create($validated);

        return redirect()->back()->with('success', 'Address added successfully');
    }

    /**
     * Get address data for editing
     */
    public function editAddress(Address $address)
    {
        // Verify address belongs to user
        if ($address->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }

        return response()->json($address);
    }

    /**
     * Update existing address
     */
    public function updateAddress(Request $request, Address $address)
    {
        // Verify address belongs to user
        if ($address->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'address_detail' => 'required|string',
            'is_default' => 'boolean'
        ]);

        // If marked as default, set others to non-default
        if ($validated['is_default']) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->back()->with('success', 'Address updated successfully');
    }

    /**
     * Delete address
     */
    public function deleteAddress(Address $address)
    {
        // Verify address belongs to user
        if ($address->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        // If deleting default address, set another address as default
        if ($address->is_default) {
            $newDefault = Auth::user()->addresses()
                ->where('id', '!=', $address->id)
                ->first();

            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $address->delete();

        return redirect()->back()->with('success', 'Address deleted successfully');
    }

    /**
     * Set address as default
     */
    public function setDefaultAddress(Address $address)
    {
        // Verify address belongs to user
        if ($address->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        // Set all addresses to non-default
        Auth::user()->addresses()->update(['is_default' => false]);

        // Set selected address as default
        $address->update(['is_default' => true]);

        return redirect()->back()->with('success', 'Default address updated successfully');
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully');
    }

    public function orders()
    {
        $orders = auth()->user()->orders()
            ->latest()
            ->paginate(10);

        return view('shop.profile.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Load order with its relationships
        $order->load(['payment', 'details.productCombination.product.images', 'address']);

        // Debug information
        \Log::info('Order Status: ' . $order->status);
        \Log::info('Payment Status: ' . ($order->payment ? $order->payment->status : 'No payment'));

        return view('shop.profile.order-detail', compact('order'));
    }
}