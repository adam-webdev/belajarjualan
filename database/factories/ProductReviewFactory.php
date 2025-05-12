<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductReviewFactory extends Factory
{
    public function definition(): array
    {
        $order = Order::factory()->create();
        $images = $this->faker->boolean(30) ? [
            'reviews/review-' . $this->faker->numberBetween(1, 5) . '-1.jpg',
            'reviews/review-' . $this->faker->numberBetween(1, 5) . '-2.jpg'
        ] : null;

        return [
            'user_id' => $order->user_id,
            'product_id' => Product::factory(),
            'order_id' => $order,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(),
            'images' => $images ? json_encode($images) : null,
            'is_verified' => $this->faker->boolean(80), // 80% chance of being verified
        ];
    }
}