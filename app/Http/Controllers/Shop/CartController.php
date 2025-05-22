<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductCombination;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        $cartItems = $cart ? $cart->items()->with(['productCombination.product.images', 'productCombination.optionValues'])->get() : collect();

        // Get shipping methods
        $shippingMethods = ShippingMethod::where('is_active', true)->get();

        // Get recently viewed products
        $recentProducts = Product::with(['images', 'combinations'])
            ->whereIn('id', session('recently_viewed', []))
            ->take(4)
            ->get();

        return view('shop.cart', compact('cart', 'cartItems', 'shippingMethods', 'recentProducts'));
    }

    /**
     * Add an item to the cart
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'combination_id' => 'required|exists:product_combinations,id'
            ]);

            // Get or create cart
            $cart = Cart::firstOrCreate(
                ['user_id' => auth()->id()],
                [
                    'subtotal' => 0,
                    'shipping_cost' => 0,
                    'discount_amount' => 0,
                    'total' => 0
                ]
            );

            // Get product combination with its option values
            $productCombination = ProductCombination::with(['optionValues'])
                ->findOrFail($request->combination_id);

            // Check if product is active
            if (!$productCombination->product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak tersedia'
                ], 422);
            }

            // Check stock
            if ($productCombination->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ], 422);
            }

            // Create new cart item for this specific variant combination
            $cart->items()->create([
                'product_combination_id' => $productCombination->id,
                'quantity' => $request->quantity
            ]);

            // Update cart totals
            $cart = $this->updateCartTotals($cart);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart' => [
                    'subtotal' => $cart->subtotal,
                    'discount_amount' => $cart->discount_amount,
                    'shipping_cost' => $cart->shipping_cost,
                    'total' => $cart->total,
                    'items_quantity' => $cart->items->sum('quantity')
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding to cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan produk ke keranjang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'cart_item_id' => 'required|exists:cart_items,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $cartItem = CartItem::where('id', $request->cart_item_id)
                ->whereHas('cart', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found'
                ], 404);
            }

            // Update quantity
            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Calculate new totals
            $cartItems = CartItem::whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })->with('productCombination')->get();

            $subtotal = $cartItems->sum(function($item) {
                return $item->productCombination->price * $item->quantity;
            });

            $total = $subtotal; // Add shipping cost if needed

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'subtotal' => $subtotal,
                'total' => $total
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_item_id' => 'required|exists:cart_items,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data',
                'errors' => $validator->errors()
            ], 422);
        }

        $cartItemId = $request->input('cart_item_id');

        // Get cart and validate ownership
        $cart = $this->getCart();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        // Get cart item and validate it belongs to user's cart
        $cartItem = CartItem::where('id', $cartItemId)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        // Remove cart item
        $cartItem->delete();

        // Update cart totals
        $this->updateCartTotals($cart);

        // Get the unique item count (number of different products)
        $uniqueItemCount = $cart->items->count();

        // Get total quantity (sum of all quantities)
        $totalQuantity = $cart->items->sum('quantity');

        // Return updated cart data for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => $uniqueItemCount,
            'cart' => [
                'subtotal' => $cart->subtotal,
                'discount_amount' => $cart->discount_amount,
                'total' => $cart->total,
                'items_count' => $uniqueItemCount,
                'items_quantity' => $totalQuantity
            ]
        ]);
    }

    /**
     * Apply a coupon to the cart
     */
    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string|exists:coupons,code'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code',
                'errors' => $validator->errors()
            ], 422);
        }

        $couponCode = $request->input('coupon_code');
        $cart = $this->getCart();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $coupon = Coupon::where('code', $couponCode)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found or inactive'
            ], 404);
        }

        // Check coupon validity
        if ($coupon->starts_at && $coupon->starts_at > now()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is not yet active'
            ], 422);
        }

        if ($coupon->expires_at && $coupon->expires_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has expired'
            ], 422);
        }

        // Check usage limits
        if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has reached its usage limit'
            ], 422);
        }

        // Check per-user usage limits if user is logged in
        if (Auth::check() && $coupon->max_uses_per_user) {
            $userUsage = $coupon->usages()->where('user_id', Auth::id())->count();
            if ($userUsage >= $coupon->max_uses_per_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already used this coupon the maximum number of times'
                ], 422);
            }
        }

        // Check minimum purchase requirement
        $subtotal = $this->calculateSubtotal($cart);
        if ($coupon->min_purchase && $subtotal < $coupon->min_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Your order does not meet the minimum purchase requirement of Rp ' . number_format($coupon->min_purchase, 0, ',', '.')
            ], 422);
        }

        // Apply the coupon
        $discountAmount = $this->calculateDiscount($coupon, $subtotal, $cart);

        // Update cart with coupon information
        $cart->update([
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
            'discount_amount' => $discountAmount
        ]);

        // Store coupon in session
        session(['applied_coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount_amount' => $discountAmount
        ]]);

        // Update cart totals
        $this->updateCartTotals($cart);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'discount_amount' => $discountAmount,
            'cart' => [
                'subtotal' => $cart->subtotal,
                'discount_amount' => $discountAmount,
                'total' => $cart->total
            ]
        ]);
    }

    /**
     * Check coupon validity via AJAX
     */
    public function checkCoupon(Request $request)
    {
        $couponCode = $request->input('code');

        $coupon = Coupon::where('code', $couponCode)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ]);
        }

        // Check coupon validity
        if ($coupon->starts_at && $coupon->starts_at > now()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is not yet active'
            ]);
        }

        if ($coupon->expires_at && $coupon->expires_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has expired'
            ]);
        }

        // Check usage limits
        if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has reached its usage limit'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Coupon is valid',
            'coupon' => [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'min_purchase' => $coupon->min_purchase,
                'max_discount' => $coupon->max_discount
            ]
        ]);
    }

    /**
     * Get the current cart (by session or user id)
     */
    private function getCart()
    {
        $query = Cart::query();

        if (Auth::check()) {
            // For logged in users, find cart by user_id
            return $query->where('user_id', Auth::id())
                    ->with(['items.productCombination.product', 'items.productCombination.optionValues'])
                    ->first();
        } else {
            // For guests, use cart_id stored in session
            $cartId = session('cart_id');
            if ($cartId) {
                return $query->where('id', $cartId)
                        ->with(['items.productCombination.product', 'items.productCombination.optionValues'])
                        ->first();
            }
        }

        return null;
    }

    /**
     * Create a new cart
     */
    private function createCart()
    {
        $cart = new Cart();

        if (Auth::check()) {
            $cart->user_id = Auth::id();
        } else {
            // For guest users, create a cart without user_id
            // We'll store the cart_id in the session
        }

        $cart->save();

        if (!Auth::check()) {
            // Store the cart ID in session for guests
            session(['cart_id' => $cart->id]);
        }

        return $cart;
    }

    /**
     * Calculate cart subtotal
     */
    private function calculateSubtotal($cart)
    {
        $subtotal = 0;

        foreach ($cart->items as $item) {
            $subtotal += $item->quantity * $item->productCombination->price;
        }

        return $subtotal;
    }

    /**
     * Calculate discount amount based on coupon
     */
    private function calculateDiscount($coupon, $subtotal, $cart)
    {
        $discountAmount = 0;

        if ($coupon->type === 'percentage') {
            $discountAmount = $subtotal * ($coupon->value / 100);

            // Apply max discount if set
            if ($coupon->max_discount && $discountAmount > $coupon->max_discount) {
                $discountAmount = $coupon->max_discount;
            }
        } else {
            // Fixed amount discount
            $discountAmount = $coupon->value;

            // Ensure discount doesn't exceed subtotal
            if ($discountAmount > $subtotal) {
                $discountAmount = $subtotal;
            }
        }

        // Check if coupon is limited to specific categories or products
        if ($coupon->categories()->count() > 0 || $coupon->products()->count() > 0) {
            $discountAmount = $this->calculateLimitedDiscount($coupon, $cart);
        }

        return $discountAmount;
    }

    /**
     * Calculate discount for category or product limited coupons
     */
    private function calculateLimitedDiscount($coupon, $cart)
    {
        $eligibleAmount = 0;
        $categoryIds = $coupon->categories()->pluck('category_id')->toArray();
        $productIds = $coupon->products()->pluck('product_id')->toArray();

        foreach ($cart->items as $item) {
            $product = $item->productCombination->product;
            $isEligible = false;

            // Check if product is in eligible categories
            if (!empty($categoryIds) && in_array($product->category_id, $categoryIds)) {
                $isEligible = true;
            }

            // Check if product is in eligible products
            if (!empty($productIds) && in_array($product->id, $productIds)) {
                $isEligible = true;
            }

            if ($isEligible) {
                $eligibleAmount += $item->quantity * $item->productCombination->price;
            }
        }

        if ($coupon->type === 'percentage') {
            $discountAmount = $eligibleAmount * ($coupon->value / 100);

            // Apply max discount if set
            if ($coupon->max_discount && $discountAmount > $coupon->max_discount) {
                $discountAmount = $coupon->max_discount;
            }
        } else {
            // Fixed amount discount
            $discountAmount = min($coupon->value, $eligibleAmount);
        }

        return $discountAmount;
    }

    /**
     * Update cart totals
     */
    private function updateCartTotals($cart)
    {
        $subtotal = $cart->items->sum(function ($item) {
            return $item->productCombination->price * $item->quantity;
        });

        $cart->update([
            'subtotal' => $subtotal,
            'total' => $subtotal - $cart->discount_amount + $cart->shipping_cost
        ]);

        return $cart->fresh();
    }

    /**
     * Update shipping cost
     */
    public function updateShippingCost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_method_id' => 'required|exists:shipping_methods,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid shipping method',
                'errors' => $validator->errors()
            ], 422);
        }

        $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);

        // Store shipping cost in session
        session(['shipping_cost' => $shippingMethod->cost]);

        // Get cart and update totals
        $cart = $this->getCart();
        if ($cart) {
            $this->updateCartTotals($cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Shipping cost updated successfully',
            'shipping_cost' => $shippingMethod->cost,
            'cart' => [
                'subtotal' => $cart->subtotal,
                'discount_amount' => $cart->discount_amount,
                'shipping_cost' => $shippingMethod->cost,
                'total' => $cart->total
            ]
        ]);
    }

    /**
     * Remove applied coupon from cart
     */
    public function removeCoupon(Request $request)
    {
        try {
            // Get the cart
            $cart = $this->getCart();
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found'
                ], 404);
            }

            // Remove coupon from cart
            $cart->update([
                'coupon_id' => null,
                'coupon_code' => null,
                'discount_amount' => 0
            ]);

            // Update cart totals
            $this->updateCartTotals($cart);

            // Remove coupon from session
            session()->forget('applied_coupon');

            return response()->json([
                'success' => true,
                'message' => 'Coupon removed successfully',
                'cart' => [
                    'subtotal' => $cart->subtotal,
                    'discount_amount' => 0,
                    'total' => $cart->total
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error removing coupon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process checkout
     */
    public function proceedToCheckout(Request $request)
    {
        try {
            // Get selected items from checkboxes
            $selectedItems = $request->input('selected_items', []);

            if (empty($selectedItems)) {
                return redirect()->back()
                    ->with('error', 'Please select at least one item to checkout');
            }

            $cart = Cart::where('user_id', auth()->id())->first();

            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Your cart is empty');
            }

            // Check stock availability for all items
            foreach ($cart->items as $item) {
                if (in_array($item->id, $selectedItems)) {
                    $combination = $item->productCombination;
                    if ($combination->stock < $item->quantity) {
                        return redirect()->back()
                            ->with('error', "Stok tidak mencukupi untuk {$combination->product->name} ({$combination->options_text}). " .
                                "Stok tersedia: {$combination->stock}, Jumlah dipesan: {$item->quantity}");
                    }
                }
            }

            // Store selected items in session
            session(['checkout_items' => $selectedItems]);

            // Redirect to checkout page
            return redirect()->route('shop.checkout');

        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to process checkout: ' . $e->getMessage());
        }
    }
}