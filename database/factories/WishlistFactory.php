<?php

namespace Database\Factories;

use App\Models\ProductCombination;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_combination_id' => ProductCombination::factory(),
        ];
    }
}