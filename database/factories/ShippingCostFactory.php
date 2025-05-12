<?php

namespace Database\Factories;

use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingCostFactory extends Factory
{
    public function definition(): array
    {
        $provinces = [
            'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Banten',
            'Sumatera Utara', 'Sumatera Barat', 'Sumatera Selatan', 'Lampung',
            'Kalimantan Barat', 'Kalimantan Timur', 'Sulawesi Selatan', 'Bali'
        ];

        $cities = [
            'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur',
            'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan', 'Palembang',
            'Makassar', 'Denpasar', 'Balikpapan', 'Pontianak'
        ];

        return [
            'shipping_method_id' => ShippingMethod::factory(),
            'province' => $this->faker->randomElement($provinces),
            'city' => $this->faker->randomElement($cities),
            'cost' => $this->faker->numberBetween(10000, 50000),
        ];
    }
}