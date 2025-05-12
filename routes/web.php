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
use Illuminate\Support\Facades\Auth;

// Redirect root to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// Authentication Routes
Auth::routes();

// Dashboard Route (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
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
    // Route::get('users/{user}/addresses', [AdminAddressController::class, 'userAddresses'])->name('addresses.user-addresses');

    // Orders (Commented out until controller is created)
    // Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    // Route::get('orders/create', [AdminOrderController::class, 'create'])->name('orders.create');
    // Route::post('orders', [AdminOrderController::class, 'store'])->name('orders.store');
    // Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    // Route::get('orders/{order}/edit', [AdminOrderController::class, 'edit'])->name('orders.edit');
    // Route::put('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    // Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // Payments (Commented out until controller is created)
    // Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    // Route::get('payments/create', [AdminPaymentController::class, 'create'])->name('payments.create');
    // Route::post('payments', [AdminPaymentController::class, 'store'])->name('payments.store');
    // Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    // Route::get('payments/{payment}/edit', [AdminPaymentController::class, 'edit'])->name('payments.edit');
    // Route::put('payments/{payment}', [AdminPaymentController::class, 'update'])->name('payments.update');
    // Route::delete('payments/{payment}', [AdminPaymentController::class, 'destroy'])->name('payments.destroy');
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');