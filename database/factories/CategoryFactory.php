<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            'Elektronik', 'Pakaian', 'Sepatu', 'Tas', 'Aksesoris',
            'Kesehatan', 'Kecantikan', 'Rumah Tangga', 'Olahraga',
            'Mainan', 'Buku', 'Makanan', 'Minuman', 'Perabotan',
            'Gadget', 'Komputer', 'Kamera', 'Audio', 'Gaming'
        ];

        // Instead of using unique()->randomElement which has a limited pool,
        // we'll use a more dynamic approach with prefixes or random strings
        if (rand(0, 1) == 1) {
            // Use base categories with random suffix
            $name = $this->faker->randomElement($categories) . ' ' . $this->faker->randomNumber(4) . ' ' . $this->faker->word;
        } else {
            // Or generate completely random category names
            $name = ucfirst($this->faker->word) . ' ' . ucfirst($this->faker->word) . ' ' . $this->faker->randomNumber(3);
        }

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5), // Add random string to ensure uniqueness
            'description' => $this->faker->paragraph(),
            'image' => 'categories/category-' . $this->faker->numberBetween(1, 6) . '.jpg',
            'is_active' => true,
        ];
    }
}