<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'coupon_id' => Coupon::factory(),
            'category_id' => Category::factory(),
        ];
    }
}