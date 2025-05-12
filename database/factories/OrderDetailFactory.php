<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProductCombination;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderDetailFactory extends Factory
{
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $price = $this->faker->numberBetween(50000, 1000000);
        $subtotal = $quantity * $price;

        return [
            'order_id' => Order::factory(),
            'product_combination_id' => ProductCombination::factory(),
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $subtotal,
        ];
    }
}