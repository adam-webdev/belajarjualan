<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\AddressController as AdminAddressController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\FlashSaleController as AdminFlashSaleController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\ProfileController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;

// Include shop routes for all nested paths
require __DIR__.'/shop.php';

// Authentication Routes
Auth::routes();

// Custom logout route to fix redirection issue
Route::post('/custom-logout', function() {
    Auth::logout();
    return redirect('/');
})->name('custom.logout');

// Dashboard Route (Protected, admin only)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect('/'); // Redirect non-admin users to homepage
        }
        return view('layouts.layoutmaster');
    })->name('dashboard');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Users
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::put('users/{user}/toggle-role', [AdminUserController::class, 'toggleRole'])->name('users.toggle-role');
    // Route::get('users/{user}/addresses', [AdminUserController::class, 'addresses'])->name('users.addresses');
    // Route::get('users/{user}/orders', [AdminUserController::class, 'orders'])->name('users.orders');
    // Route::get('users/{user}/wishlist', [AdminUserController::class, 'wishlist'])->name('users.wishlist');
    // Route::get('users/{user}/reviews', [AdminUserController::class, 'reviews'])->name('users.reviews');

    // Categories
    Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}', [AdminCategoryController::class, 'show'])->name('categories.show');
    Route::get('categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::put('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::post('categories/batch-delete', [AdminCategoryController::class, 'batchDelete'])->name('categories.batch-delete');
    Route::get('categories/trash/view', [AdminCategoryController::class, 'trash'])->name('categories.trash');
    Route::post('categories/trash/{id}/restore', [AdminCategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/trash/{id}/force-delete', [AdminCategoryController::class, 'forceDelete'])->name('categories.force-delete');
    Route::post('categories/batch-restore', [AdminCategoryController::class, 'batchRestore'])->name('categories.batch-restore');
    Route::delete('categories/batch-force-delete', [AdminCategoryController::class, 'batchForceDelete'])->name('categories.batch-force-delete');
    // Route::get('categories/{category}/products', [AdminCategoryController::class, 'products'])->name('categories.products');

    // Products
    Route::get('products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
    Route::get('products/{product}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('products/{product}/images', [App\Http\Controllers\Admin\ProductController::class, 'addImages'])->name('products.images.store');
    Route::delete('products/images/{image}', [App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.images.destroy');
    Route::put('products/images/{image}/primary', [App\Http\Controllers\Admin\ProductController::class, 'setPrimaryImage'])->name('products.images.primary');
    Route::get('products/{product}/combinations', [App\Http\Controllers\Admin\ProductController::class, 'getCombinations'])->name('products.combinations.get');
    Route::get('products/{product}/data', [App\Http\Controllers\Admin\ProductController::class, 'getProductData'])->name('products.data.get');

    // Product Variants
    Route::post('products/{product}/options', [App\Http\Controllers\Admin\ProductController::class, 'addOption'])->name('products.options.store');
    Route::get('products/options/{option}/edit', [App\Http\Controllers\Admin\ProductController::class, 'editOption'])->name('products.options.edit');
    Route::put('products/options/{option}', [App\Http\Controllers\Admin\ProductController::class, 'updateOption'])->name('products.options.update');
    Route::delete('products/options/{option}', [App\Http\Controllers\Admin\ProductController::class, 'deleteOption'])->name('products.options.destroy');
    Route::post('products/options/{option}/values', [App\Http\Controllers\Admin\ProductController::class, 'addOptionValue'])->name('products.option-values.store');
    Route::delete('products/option-values/{value}', [App\Http\Controllers\Admin\ProductController::class, 'deleteOptionValue'])->name('products.option-values.destroy');

    Route::post('products/{product}/combinations', [App\Http\Controllers\Admin\ProductController::class, 'addCombination'])->name('products.combinations.store');
    Route::get('products/combinations/{product}', [App\Http\Controllers\Admin\ProductController::class, 'manageCombinations'])->name('products.combinations.manage');
    Route::put('products/combinations/{combination}', [App\Http\Controllers\Admin\ProductController::class, 'updateCombination'])->name('products.combinations.update');
    Route::delete('products/combinations/{combination}', [App\Http\Controllers\Admin\ProductController::class, 'deleteCombination'])->name('products.combinations.destroy');
    Route::post('products/{product}/generate-combinations', [App\Http\Controllers\Admin\ProductController::class, 'generateCombinations'])->name('products.combinations.generate');

    // Addresses
    Route::get('addresses', [AdminAddressController::class, 'index'])->name('addresses.index');
    Route::get('addresses/create', [AdminAddressController::class, 'create'])->name('addresses.create');
    Route::post('addresses', [AdminAddressController::class, 'store'])->name('addresses.store');
    Route::get('addresses/{address}', [AdminAddressController::class, 'show'])->name('addresses.show');
    Route::get('addresses/{address}/edit', [AdminAddressController::class, 'edit'])->name('addresses.edit');
    Route::put('addresses/{address}', [AdminAddressController::class, 'update'])->name('addresses.update');
    Route::delete('addresses/{address}', [AdminAddressController::class, 'destroy'])->name('addresses.destroy');
    Route::put('addresses/{address}/set-default', [AdminAddressController::class, 'setDefault'])->name('addresses.set-default');
    Route::get('orders/get-user-addresses', [OrderController::class, 'getUserAddresses'])->name('orders.get-user-addresses');
    Route::get('orders/get-product-combinations', [OrderController::class, 'getProductCombinations'])->name('orders.get-product-combinations');
    Route::post('orders/calculate-shipping', [OrderController::class, 'calculateShippingCost'])->name('orders.calculate-shipping');
    Route::post('orders/save-address', [OrderController::class, 'saveAddress'])->name('orders.save-address');

    // Shipping Methods
    Route::get('shipping/methods', [App\Http\Controllers\Admin\ShippingMethodController::class, 'index'])->name('shipping.methods.index');
    Route::get('shipping/methods/create', [App\Http\Controllers\Admin\ShippingMethodController::class, 'create'])->name('shipping.methods.create');
    Route::post('shipping/methods', [App\Http\Controllers\Admin\ShippingMethodController::class, 'store'])->name('shipping.methods.store');
    Route::get('shipping/methods/{method}', [App\Http\Controllers\Admin\ShippingMethodController::class, 'show'])->name('shipping.methods.show');
    Route::get('shipping/methods/{method}/edit', [App\Http\Controllers\Admin\ShippingMethodController::class, 'edit'])->name('shipping.methods.edit');
    Route::put('shipping/methods/{method}', [App\Http\Controllers\Admin\ShippingMethodController::class, 'update'])->name('shipping.methods.update');
    Route::delete('shipping/methods/{method}', [App\Http\Controllers\Admin\ShippingMethodController::class, 'destroy'])->name('shipping.methods.destroy');
    Route::put('shipping/methods/{method}/toggle-status', [App\Http\Controllers\Admin\ShippingMethodController::class, 'toggleStatus'])->name('shipping.methods.toggle-status');
    Route::get('shipping/methods/all', [App\Http\Controllers\Admin\ShippingMethodController::class, 'getAllMethods'])->name('shipping.methods.all');

    // Shipping Costs
    Route::get('shipping/costs', [App\Http\Controllers\Admin\ShippingCostController::class, 'index'])->name('shipping.costs.index');
    Route::get('shipping/costs/create', [App\Http\Controllers\Admin\ShippingCostController::class, 'create'])->name('shipping.costs.create');
    Route::post('shipping/costs', [App\Http\Controllers\Admin\ShippingCostController::class, 'store'])->name('shipping.costs.store');
    Route::get('shipping/costs/{cost}', [App\Http\Controllers\Admin\ShippingCostController::class, 'show'])->name('shipping.costs.show');
    Route::get('shipping/costs/{cost}/edit', [App\Http\Controllers\Admin\ShippingCostController::class, 'edit'])->name('shipping.costs.edit');
    Route::put('shipping/costs/{cost}', [App\Http\Controllers\Admin\ShippingCostController::class, 'update'])->name('shipping.costs.update');
    Route::delete('shipping/costs/{cost}', [App\Http\Controllers\Admin\ShippingCostController::class, 'destroy'])->name('shipping.costs.destroy');
    Route::get('shipping/methods/{method}/costs', [App\Http\Controllers\Admin\ShippingCostController::class, 'byMethod'])->name('shipping.costs.by-method');
    Route::get('shipping/costs/calculate', [App\Http\Controllers\Admin\ShippingCostController::class, 'calculateCost'])->name('shipping.costs.calculate');

    // Coupons
    Route::get('coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::get('coupons/create', [AdminCouponController::class, 'create'])->name('coupons.create');
    Route::post('coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::get('coupons/{coupon}', [AdminCouponController::class, 'show'])->name('coupons.show');
    Route::get('coupons/{coupon}/edit', [AdminCouponController::class, 'edit'])->name('coupons.edit');
    Route::put('coupons/{coupon}', [AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');
    Route::put('coupons/{coupon}/toggle-status', [AdminCouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::get('coupons/generate-code', [AdminCouponController::class, 'generateCode'])->name('coupons.generate-code');

    // Flash Sales
    Route::get('flash-sales', [AdminFlashSaleController::class, 'index'])->name('flash-sales.index');
    Route::get('flash-sales/create', [AdminFlashSaleController::class, 'create'])->name('flash-sales.create');
    Route::post('flash-sales', [AdminFlashSaleController::class, 'store'])->name('flash-sales.store');
    Route::get('flash-sales/{flashSale}', [AdminFlashSaleController::class, 'show'])->name('flash-sales.show');
    Route::get('flash-sales/{flashSale}/edit', [AdminFlashSaleController::class, 'edit'])->name('flash-sales.edit');
    Route::put('flash-sales/{flashSale}', [AdminFlashSaleController::class, 'update'])->name('flash-sales.update');
    Route::delete('flash-sales/{flashSale}', [AdminFlashSaleController::class, 'destroy'])->name('flash-sales.destroy');
    Route::put('flash-sales/{flashSale}/toggle-status', [AdminFlashSaleController::class, 'toggleStatus'])->name('flash-sales.toggle-status');
    Route::post('flash-sales/{flashSale}/items', [AdminFlashSaleController::class, 'addItems'])->name('flash-sales.items.store');
    Route::put('flash-sales/items/{item}', [AdminFlashSaleController::class, 'updateItem'])->name('flash-sales.items.update');
    Route::delete('flash-sales/items/{item}', [AdminFlashSaleController::class, 'removeItem'])->name('flash-sales.items.destroy');

    // Orders
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [App\Http\Controllers\Admin\OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [App\Http\Controllers\Admin\OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/edit', [App\Http\Controllers\Admin\OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('orders/get-product-price', [App\Http\Controllers\Admin\OrderController::class, 'getProductPrice'])->name('orders.get-product-price');

    // Payments
    Route::get('payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::put('payments/{payment}/status', [App\Http\Controllers\Admin\PaymentController::class, 'updateStatus'])->name('payments.update-status');
    Route::post('payments/check-expired', [App\Http\Controllers\Admin\PaymentController::class, 'checkExpiredPayments'])->name('payments.check-expired');
    Route::delete('/payments/{payment}/delete-failed', [PaymentController::class, 'deleteFailedPayment'])->name('payments.delete-failed');
});

// Shop Routes
Route::prefix('shop')->name('shop.')->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'proceedToCheckout'])->name('cart.proceed-to-checkout');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/order/success/{orderId}', [CheckoutController::class, 'success'])->name('order.success');

    // Profile Routes
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{order}', [ProfileController::class, 'orderDetail'])->name('profile.order.detail');
});

// Shop Profile Routes
Route::middleware(['auth'])->prefix('shop/profile')->name('shop.profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');

    // Orders Routes
    Route::get('/orders', [ProfileController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [ProfileController::class, 'orderDetail'])->name('order.detail');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('orders.confirm-delivery');
    Route::post('/orders/{order}/upload-payment', [OrderController::class, 'uploadPaymentProof'])->name('orders.upload-payment');

    // Address Routes
    Route::post('/address', [ProfileController::class, 'storeAddress'])->name('address.store');
    Route::get('/address/{address}/edit', [ProfileController::class, 'editAddress'])->name('address.edit');
    Route::put('/address/{address}', [ProfileController::class, 'updateAddress'])->name('address.update');
    Route::delete('/address/{address}', [ProfileController::class, 'deleteAddress'])->name('address.delete');
    Route::post('/address/{address}/set-default', [ProfileController::class, 'setDefaultAddress'])->name('address.set-default');
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');