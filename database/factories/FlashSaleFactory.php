<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FlashSaleFactory extends Factory
{
    public function definition(): array
    {
        $startTime = now()->addDays($this->faker->numberBetween(1, 7));
        $endTime = $startTime->copy()->addDays($this->faker->numberBetween(1, 3));

        return [
            'name' => 'Flash Sale ' . $this->faker->words(2, true),
            'description' => $this->faker->paragraph(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_active' => true,
        ];
    }
}