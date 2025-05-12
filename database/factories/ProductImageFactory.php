<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'image_path' => 'products/product-' . $this->faker->numberBetween(1, 10) . '.jpg',
            'is_primary' => $this->faker->boolean(20), // 20% chance of being primary image
        ];
    }
}