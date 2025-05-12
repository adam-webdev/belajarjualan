<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $basePrice = $this->faker->numberBetween(50000, 5000000);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(3, true),
            'base_price' => $basePrice,
            'has_variant' => $this->faker->boolean(30), // 30% chance of having variants
            'is_active' => true,
        ];
    }
}