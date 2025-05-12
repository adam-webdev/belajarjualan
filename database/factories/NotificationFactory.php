<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        $types = ['order_status', 'payment', 'promo', 'system'];
        $type = $this->faker->randomElement($types);

        $titles = [
            'order_status' => ['Pesanan Diproses', 'Pesanan Dikirim', 'Pesanan Diterima'],
            'payment' => ['Pembayaran Berhasil', 'Pembayaran Gagal', 'Pembayaran Pending'],
            'promo' => ['Promo Spesial', 'Flash Sale', 'Diskon Akhir Tahun'],
            'system' => ['Pembaruan Sistem', 'Maintenance', 'Fitur Baru']
        ];

        $messages = [
            'order_status' => [
                'Pesanan Anda sedang diproses',
                'Pesanan Anda telah dikirim',
                'Pesanan Anda telah diterima'
            ],
            'payment' => [
                'Pembayaran Anda telah berhasil',
                'Pembayaran Anda gagal, silakan coba lagi',
                'Pembayaran Anda sedang diproses'
            ],
            'promo' => [
                'Dapatkan diskon spesial untuk produk pilihan',
                'Flash sale akan dimulai dalam 1 jam',
                'Diskon hingga 50% untuk semua produk'
            ],
            'system' => [
                'Sistem telah diperbarui',
                'Akan ada maintenance pada pukul 00:00',
                'Fitur baru telah tersedia'
            ]
        ];

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->randomElement($titles[$type]),
            'message' => $this->faker->randomElement($messages[$type]),
            'type' => $type,
            'is_read' => $this->faker->boolean(30), // 30% chance of being read
        ];
    }
}