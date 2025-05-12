<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Users & Addresses
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('recipient_name');
            $table->string('phone');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('postal_code');
            $table->text('address_detail');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Categories & Products
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('base_price', 12, 2);
            $table->boolean('has_variant')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Product Variants
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., 'Color', 'Size'
            $table->timestamps();
        });

        Schema::create('product_option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_option_id')->constrained()->onDelete('cascade');
            $table->string('value'); // e.g., 'Red', 'Blue', 'XL', 'L'
            $table->timestamps();
        });

        Schema::create('product_combinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->decimal('price', 12, 2);
            $table->integer('stock');
            $table->decimal('weight', 8, 2); // in grams
            $table->timestamps();
        });

        Schema::create('product_combination_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_combination_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_option_value_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Shipping Methods
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('shipping_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_method_id')->constrained()->onDelete('cascade');
            $table->string('province');
            $table->string('city');
            $table->decimal('cost', 12, 2);
            $table->timestamps();
        });

        // Coupons
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name'); // Nama kupon untuk admin
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 12, 2); // Nilai diskon (persentase atau nominal)
            $table->decimal('min_purchase', 12, 2)->nullable(); // Minimum pembelian
            $table->decimal('max_discount', 12, 2)->nullable(); // Maksimum diskon untuk kupon persentase
            $table->integer('max_uses')->nullable(); // Maksimum penggunaan
            $table->integer('used_count')->default(0); // Jumlah penggunaan
            $table->integer('max_uses_per_user')->nullable(); // Maksimum penggunaan per user
            $table->timestamp('starts_at')->nullable(); // Mulai berlaku
            $table->timestamp('expires_at')->nullable(); // Berakhir
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('coupon_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('coupon_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Flash Sales
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('flash_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flash_sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_combination_id')->constrained()->onDelete('cascade');
            $table->integer('stock_available');
            $table->integer('stock_sold')->default(0);
            $table->decimal('discount_price', 12, 2);
            $table->integer('purchase_limit')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Cart & Orders
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_combination_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            $table->string('coupon_code')->nullable();
            $table->decimal('total', 12, 2);
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->string('tracking_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_combination_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method');
            $table->decimal('amount', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            $table->string('coupon_code')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('payment_proof')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Reviews & Ratings
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        // Coupon Usages
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('discount_amount', 12, 2);
            $table->timestamps();
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Wishlist
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_combination_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Tambahkan unique constraint agar user tidak bisa menambahkan produk yang sama ke wishlist
            $table->unique(['user_id', 'product_combination_id']);
        });
    }

    public function down()
    {
        // Drop tables in reverse order
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('flash_sale_items');
        Schema::dropIfExists('flash_sales');
        Schema::dropIfExists('coupon_products');
        Schema::dropIfExists('coupon_categories');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('shipping_costs');
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('product_combination_values');
        Schema::dropIfExists('product_combinations');
        Schema::dropIfExists('product_option_values');
        Schema::dropIfExists('product_options');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('users');
    }
};