<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCombination;
use App\Models\ShippingMethod;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ShippingMethodSeeder::class,
        ]);

        // Truncate tables in reverse order to avoid constraint violations
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('wishlists')->truncate();
        DB::table('notifications')->truncate();
        DB::table('coupon_usages')->truncate();
        DB::table('product_reviews')->truncate();
        DB::table('payments')->truncate();
        DB::table('order_details')->truncate();
        DB::table('orders')->truncate();
        DB::table('cart_items')->truncate();
        DB::table('carts')->truncate();
        DB::table('product_combination_values')->truncate();
        DB::table('product_combinations')->truncate();
        DB::table('product_option_values')->truncate();
        DB::table('product_options')->truncate();
        DB::table('product_images')->truncate();
        DB::table('coupon_products')->truncate();
        DB::table('coupon_categories')->truncate();
        DB::table('coupons')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('addresses')->truncate();
        DB::table('shipping_methods')->truncate();
        DB::table('shipping_costs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Users - create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Categories (20 categories with unique names/slugs)
        $categories = Category::factory(20)->create();

        // 3. Products (5 products per category = 100 products total)
        foreach ($categories as $category) {
            for ($i = 1; $i <= 5; $i++) {
                $productName = "Produk {$category->name} $i";
                Product::create([
                    'category_id' => $category->id,
                    'name' => $productName,
                    'slug' => Str::slug($productName) . '-' . Str::random(5), // Ensure unique slugs
                    'description' => "Deskripsi untuk produk {$category->name} $i",
                    'base_price' => rand(50000, 1000000),
                    'has_variant' => rand(0, 1),
                    'is_active' => true,
                ]);
            }
        }

        // 4. Shipping Methods
        \App\Models\ShippingMethod::factory(5)->create();

        // 5. Create dummy address for admin
        $adminAddress = DB::table('addresses')->insertGetId([
            'user_id' => $admin->id,
            'recipient_name' => 'Admin',
            'phone' => '081234567890',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12170',
            'address_detail' => 'Jl. Contoh No. 123',
            'is_default' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Create dummy order for admin
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $orderId = DB::table('orders')->insertGetId([
            'user_id' => $admin->id,
            'address_id' => $adminAddress,
            'order_number' => $orderNumber,
            'subtotal' => 500000,
            'shipping_cost' => 15000,
            'discount_amount' => 0,
            'total' => 515000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 7. Create payment for the order
        DB::table('payments')->insert([
            'order_id' => $orderId,
            'payment_method' => 'bank_transfer',
            'amount' => 515000,
            'discount_amount' => 0,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 8. Create regular users
        User::factory(9)->create(); // 9 + admin = 10 users

        // 9. Create addresses for regular users (2 per user)
        $regularUsers = User::where('id', '!=', $admin->id)->get();
        foreach ($regularUsers as $user) {
            \App\Models\Address::factory(2)->create(['user_id' => $user->id]);
        }

        // 10. Create product images (3-5 images per product)
        $products = Product::all();
        foreach ($products as $product) {
            $imageCount = rand(3, 5);
            for ($i = 1; $i <= $imageCount; $i++) {
                $isPrimary = ($i === 1); // First image is primary
                DB::table('product_images')->insert([
                    'product_id' => $product->id,
                    'image_path' => 'products/product-' . rand(1, 10) . '.jpg',
                    'is_primary' => $isPrimary,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create a reasonable number of each entity to avoid foreign key issues

        // Create product options - linked to real products
        $products->each(function ($product) {
            if ($product->has_variant) {
                \App\Models\ProductOption::factory(rand(1, 2))->create([
                    'product_id' => $product->id
                ]);
            }
        });

        // Create option values for real options
        $options = \App\Models\ProductOption::all();
        $options->each(function ($option) {
            \App\Models\ProductOptionValue::factory(rand(2, 4))->create([
                'product_option_id' => $option->id
            ]);
        });

        // Create product combinations for products with variants
        $variantProducts = Product::where('has_variant', true)->get();
        $variantProducts->each(function ($product) {
            \App\Models\ProductCombination::factory(rand(2, 4))->create([
                'product_id' => $product->id
            ]);
        });

        // Create product combination values
        $combinations = \App\Models\ProductCombination::all();
        $combinations->each(function ($combination) {
            $product = $combination->product;
            $options = $product->options;

            if ($options->count() > 0) {
                foreach ($options as $option) {
                    $optionValue = $option->values()->inRandomOrder()->first();
                    if ($optionValue) {
                        \App\Models\ProductCombinationValue::create([
                            'product_combination_id' => $combination->id,
                            'product_option_value_id' => $optionValue->id
                        ]);
                    }
                }
            }
        });

        // Add more conservative numbers for the remaining entities

        // Create shipping costs for real shipping methods
        \App\Models\ShippingCost::factory(20)->create();

        // Create coupons
        \App\Models\Coupon::factory(5)->create();

        // Create coupon relations
        $coupons = \App\Models\Coupon::all();
        foreach ($coupons as $coupon) {
            // Add 1-3 categories to each coupon
            $categoryIds = $categories->random(rand(1, 3))->pluck('id');
            foreach ($categoryIds as $categoryId) {
                \App\Models\CouponCategory::create([
                    'coupon_id' => $coupon->id,
                    'category_id' => $categoryId
                ]);
            }

            // Add 1-3 products to each coupon
            $productIds = $products->random(rand(1, 3))->pluck('id');
            foreach ($productIds as $productId) {
                \App\Models\CouponProduct::create([
                    'coupon_id' => $coupon->id,
                    'product_id' => $productId
                ]);
            }
        }

        // Create carts for real users
        $users = User::all();
        foreach ($users as $user) {
            \App\Models\Cart::create(['user_id' => $user->id]);
        }

        // Create cart items for real carts and combinations
        $carts = \App\Models\Cart::all();
        $combinations = \App\Models\ProductCombination::all();
        if ($combinations->count() > 0) {
            foreach ($carts as $cart) {
                // Add 1-3 items to each cart
                for ($i = 0; $i < rand(1, 3); $i++) {
                    \App\Models\CartItem::create([
                        'cart_id' => $cart->id,
                        'product_combination_id' => $combinations->random()->id,
                        'quantity' => rand(1, 3)
                    ]);
                }
            }
        }

        // Create sample order details for the admin order
        $combinations = \App\Models\ProductCombination::all();
        if ($combinations->count() > 0) {
            for ($i = 0; $i < 2; $i++) {
                $combo = $combinations->random();
                \App\Models\OrderDetail::create([
                    'order_id' => $orderId,
                    'product_combination_id' => $combo->id,
                    'quantity' => rand(1, 2),
                    'price' => $combo->price ?? 100000,
                    'subtotal' => ($combo->price ?? 100000) * rand(1, 2)
                ]);
            }
        }

        // Create a moderate number of reviews
        \App\Models\ProductReview::factory(30)->create();

        // Create coupon usages
        $coupons = \App\Models\Coupon::all();
        $users = User::all();
        $orders = \App\Models\Order::where('coupon_id', '!=', null)->get();

        if ($coupons->count() > 0 && $orders->count() > 0) {
            foreach ($orders->take(10) as $order) {
                \App\Models\CouponUsage::create([
                    'coupon_id' => $order->coupon_id,
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'discount_amount' => $order->discount_amount,
                ]);
            }
        }

        // Create notifications (a few per user)
        foreach ($users as $user) {
            \App\Models\Notification::factory(rand(3, 5))->create([
                'user_id' => $user->id
            ]);
        }

        // Create wishlists
        $combinations = \App\Models\ProductCombination::all();
        if ($combinations->count() > 0) {
            foreach ($users as $user) {
                for ($i = 0; $i < rand(0, 3); $i++) {
                    try {
                        \App\Models\Wishlist::create([
                            'user_id' => $user->id,
                            'product_combination_id' => $combinations->random()->id
                        ]);
                    } catch (\Exception $e) {
                        // Skip if duplicate combination for user
                        continue;
                    }
                }
            }
        }
    }
}
































  // $user = new \App\Models\User();
        // $user->name = 'Adminbmb';
        // $user->email = 'adminbmb@gmail.com';
        // $user->password = bcrypt('pwnyaapaemang00');
        // $user->save();