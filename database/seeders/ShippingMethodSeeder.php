<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Standard Shipping',
                'description' => 'Regular shipping with 3-5 business days delivery',
                'cost' => 20000,
                'is_active' => true,
                'estimated_days' => 5
            ],
            [
                'name' => 'Express Shipping',
                'description' => 'Fast shipping with 1-2 business days delivery',
                'cost' => 35000,
                'is_active' => true,
                'estimated_days' => 2
            ],
            [
                'name' => 'Same Day Delivery',
                'description' => 'Delivery on the same day (available in selected areas)',
                'cost' => 50000,
                'is_active' => true,
                'estimated_days' => 1
            ]
        ];

        foreach ($methods as $method) {
            ShippingMethod::create($method);
        }
    }
}