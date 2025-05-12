<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(['percentage', 'fixed']);
        $value = $type === 'percentage'
            ? $this->faker->numberBetween(5, 50) // 5-50%
            : $this->faker->numberBetween(10000, 100000); // Rp 10.000 - Rp 100.000

        return [
            'code' => 'DISKON' . strtoupper(Str::random(5)),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'type' => $type,
            'value' => $value,
            'min_purchase' => $this->faker->numberBetween(100000, 500000),
            'max_discount' => $type === 'percentage' ? $this->faker->numberBetween(50000, 200000) : null,
            'max_uses' => $this->faker->numberBetween(100, 1000),
            'used_count' => 0,
            'max_uses_per_user' => $this->faker->numberBetween(1, 3),
            'starts_at' => now(),
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ];
    }
}