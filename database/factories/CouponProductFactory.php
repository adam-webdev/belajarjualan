<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'coupon_id' => Coupon::factory(),
            'product_id' => Product::factory(),
        ];
    }
}