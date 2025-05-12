<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductCombinationFactory extends Factory
{
    public function definition(): array
    {
        $basePrice = $this->faker->numberBetween(50000, 5000000);
        $price = $this->faker->numberBetween($basePrice * 0.8, $basePrice * 1.2); // ±20% from base price

        return [
            'product_id' => Product::factory(),
            'sku' => 'SKU-' . strtoupper(Str::random(8)),
            'price' => $price,
            'stock' => $this->faker->numberBetween(0, 100),
            'weight' => $this->faker->numberBetween(100, 5000), // 100g - 5kg
        ];
    }
}