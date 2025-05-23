<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\ProfileController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\WishlistController;

/*
|--------------------------------------------------------------------------
| Shop Routes
|--------------------------------------------------------------------------
|
| These routes are for the frontend shopping experience
|
*/

// Home page
Route::get('/', [HomeController::class, 'index'])->name('shop.home');

// Products & Categories
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('shop.category');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('shop.product');
Route::get('/search', [ProductController::class, 'search'])->name('shop.search');
Route::get('/flash-sales', [ProductController::class, 'flashSales'])->name('shop.flash-sales');
Route::get('/new-arrivals', [ProductController::class, 'newArrivals'])->name('shop.new-arrivals');
Route::get('/best-sellers', [ProductController::class, 'bestSellers'])->name('shop.best-sellers');
Route::get('/products/variants', [ProductController::class, 'getVariants'])->name('shop.product.variants');

// Cart
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('shop.cart');
    Route::post('/add', [CartController::class, 'add'])->name('shop.cart.add');
    Route::post('/update', [CartController::class, 'update'])->name('shop.cart.update');
    Route::post('/remove', [CartController::class, 'remove'])->name('shop.cart.remove');
    Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('shop.cart.apply-coupon');
    Route::post('/remove-coupon', [CartController::class, 'removeCoupon'])->name('shop.cart.remove-coupon');
    Route::post('/update-shipping', [CartController::class, 'updateShippingCost'])->name('shop.cart.update-shipping');
    Route::post('/proceed-to-checkout', [CartController::class, 'proceedToCheckout'])->name('shop.cart.proceed-to-checkout');
});

// Checkout
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('shop.checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('shop.checkout.process');
    Route::get('/order-confirmation/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('shop.checkout.confirmation');

    // User profile and orders
    Route::get('/profile', [ProfileController::class, 'index'])->name('shop.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('shop.profile.update');
    Route::get('/orders', [OrderController::class, 'index'])->name('shop.orders');
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('shop.orders.show');

    // Address management
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('shop.profile.addresses.store');
    Route::get('/profile/addresses/{address}/edit', [ProfileController::class, 'editAddress'])->name('shop.profile.addresses.edit');
    Route::put('/profile/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('shop.profile.addresses.update');
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'deleteAddress'])->name('shop.profile.addresses.delete');
    Route::put('/profile/addresses/{address}/default', [ProfileController::class, 'setDefaultAddress'])->name('shop.profile.addresses.default');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('shop.wishlist');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('shop.wishlist.add');
    Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('shop.wishlist.remove');

    // Password change
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('shop.profile.password');

    // Additional order routes
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('shop.orders.cancel');
    Route::post('/orders/{order}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('shop.orders.confirm-delivery');
    Route::post('/orders/{order}/upload-payment', [OrderController::class, 'uploadPaymentProof'])->name('shop.orders.upload-payment');

    // Additional wishlist routes
    Route::post('/wishlist/add-all-to-cart', [WishlistController::class, 'addAllToCart'])->name('shop.wishlist.add-all-to-cart');
    Route::post('/wishlist/{id}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('shop.wishlist.move-to-cart');
});