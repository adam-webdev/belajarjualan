<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\ProductCombination;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'product_combination_id' => ProductCombination::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}