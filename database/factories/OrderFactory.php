<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(100000, 5000000);
        $shippingCost = $this->faker->numberBetween(10000, 50000);
        $discountAmount = $this->faker->numberBetween(0, $subtotal * 0.2); // Max 20% discount
        $total = $subtotal + $shippingCost - $discountAmount;

        $status = $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']);
        $trackingNumber = $status !== 'pending' ? 'TRK-' . strtoupper(Str::random(10)) : null;

        return [
            'user_id' => User::factory(),
            'address_id' => Address::factory(),
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount_amount' => $discountAmount,
            'coupon_id' => $this->faker->boolean(30) ? Coupon::factory() : null,
            'coupon_code' => $this->faker->boolean(30) ? 'DISKON' . strtoupper(Str::random(5)) : null,
            'total' => $total,
            'status' => $status,
            'tracking_number' => $trackingNumber,
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}