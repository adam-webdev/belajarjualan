<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display user wishlist
     */
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with(['productCombination.product.images'])
            ->paginate(12);

        return view('shop.wishlist', [
            'wishlistItems' => $wishlistItems
        ]);
    }

    /**
     * Add a product to wishlist
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_combination_id' => 'required|exists:product_combinations,id',
        ]);

        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to add items to your wishlist.'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to add items to your wishlist.');
        }

        // Check if product is already in wishlist
        $exists = Wishlist::isInWishlist(Auth::id(), $validated['product_combination_id']);

        if ($exists) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product is already in your wishlist.',
                    'isNew' => false
                ]);
            }
            return back()->with('info', 'Product is already in your wishlist.');
        }

        // Add to wishlist
        Wishlist::addItem(Auth::id(), $validated['product_combination_id']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist successfully.',
                'isNew' => true
            ]);
        }

        return back()->with('success', 'Product added to wishlist successfully.');
    }

    /**
     * Remove a product from wishlist
     */
    public function remove($id)
    {
        $wishlistItem = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $wishlistItem->delete();

        return back()->with('success', 'Product removed from wishlist successfully.');
    }

    /**
     * Add all wishlist items to cart
     */
    public function addAllToCart()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())->get();

        if ($wishlistItems->isEmpty()) {
            return back()->with('error', 'Your wishlist is empty.');
        }

        Wishlist::addAllToCart(Auth::id());

        return redirect()->route('shop.cart')->with('success', 'All wishlist items added to cart.');
    }

    /**
     * Move a wishlist item to cart
     */
    public function moveToCart($id)
    {
        $wishlistItem = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (Wishlist::moveToCart(Auth::id(), $id)) {
            return back()->with('success', 'Product moved to cart successfully.');
        }

        return back()->with('error', 'Failed to move product to cart.');
    }
}