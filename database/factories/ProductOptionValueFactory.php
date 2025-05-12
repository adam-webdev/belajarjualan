<?php

namespace Database\Factories;

use App\Models\ProductOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductOptionValueFactory extends Factory
{
    public function definition(): array
    {
        $optionValues = [
            'Ukuran' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'Warna' => ['Merah', 'Biru', 'Hitam', 'Putih', 'Hijau', 'Kuning', 'Ungu', 'Abu-abu'],
            'Material' => ['Katun', 'Polyester', 'Denim', 'Sutra', 'Wol', 'Kulit'],
            'Tipe' => ['Regular', 'Premium', 'Limited Edition', 'Standard'],
            'Varian' => ['A', 'B', 'C', 'D', 'E']
        ];

        $option = ProductOption::factory()->create();
        $values = $optionValues[$option->name] ?? ['Default'];

        return [
            'product_option_id' => $option,
            'value' => $this->faker->randomElement($values),
        ];
    }
}