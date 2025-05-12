<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponUsageFactory extends Factory
{
    public function definition(): array
    {
        $coupon = Coupon::inRandomOrder()->first() ?? Coupon::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
            'discount_amount' => $this->faker->numberBetween(5000, 50000),
        ]);

        return [
            'coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'order_id' => $order->id,
            'discount_amount' => $order->discount_amount,
        ];
    }
}