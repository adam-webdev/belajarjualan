<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductOptionFactory extends Factory
{
    public function definition(): array
    {
        $optionTypes = ['Ukuran', 'Warna', 'Material', 'Tipe', 'Varian'];

        return [
            'product_id' => Product::factory(),
            'name' => $this->faker->randomElement($optionTypes),
        ];
    }
}