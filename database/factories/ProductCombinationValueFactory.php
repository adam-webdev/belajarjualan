<?php

namespace Database\Factories;

use App\Models\ProductCombination;
use App\Models\ProductOptionValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCombinationValueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_combination_id' => ProductCombination::factory(),
            'product_option_value_id' => ProductOptionValue::factory(),
        ];
    }
}