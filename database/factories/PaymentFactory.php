<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $order = Order::factory()->create();
        $status = $this->faker->randomElement(['pending', 'paid', 'failed']);
        $paidAt = $status === 'paid' ? now() : null;

        return [
            'order_id' => $order,
            'payment_method' => $this->faker->randomElement(['bank_transfer', 'e_wallet', 'credit_card']),
            'amount' => $order->total,
            'discount_amount' => $order->discount_amount,
            'coupon_id' => $order->coupon_id,
            'coupon_code' => $order->coupon_code,
            'status' => $status,
            'payment_proof' => $status === 'paid' ? 'payments/payment-' . $this->faker->numberBetween(1, 5) . '.jpg' : null,
            'paid_at' => $paidAt,
        ];
    }
}