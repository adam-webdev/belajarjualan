<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
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

        $districts = [
            'Menteng', 'Kemayoran', 'Tebet', 'Kebayoran', 'Grogol', 'Kemanggisan',
            'Senen', 'Cikini', 'Manggarai', 'Kuningan', 'Sudirman', 'Thamrin'
        ];

        return [
            'user_id' => User::factory(),
            'recipient_name' => $this->faker->name(),
            'phone' => '08' . $this->faker->numerify('##########'),
            'province' => $this->faker->randomElement($provinces),
            'city' => $this->faker->randomElement($cities),
            'district' => $this->faker->randomElement($districts),
            'postal_code' => $this->faker->numerify('#####'),
            'address_detail' => $this->faker->streetAddress(),
            'is_default' => $this->faker->boolean(20), // 20% chance of being default
        ];
    }
}