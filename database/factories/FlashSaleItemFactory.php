<?php

namespace Database\Factories;

use App\Models\FlashSale;
use App\Models\ProductCombination;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlashSaleItemFactory extends Factory
{
    public function definition(): array
    {
        $productCombination = ProductCombination::factory()->create();
        $discountPrice = $productCombination->price * $this->faker->randomFloat(2, 0.5, 0.8); // 20-50% discount

        return [
            'flash_sale_id' => FlashSale::factory(),
            'product_combination_id' => $productCombination,
            'stock_available' => $this->faker->numberBetween(10, 100),
            'stock_sold' => $this->faker->numberBetween(0, 50),
            'discount_price' => $discountPrice,
            'purchase_limit' => $this->faker->numberBetween(1, 5),
            'is_active' => true,
        ];
    }
}