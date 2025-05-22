<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Bank Central Asia (BCA)',
                'code' => 'bca',
                'type' => 'bank_transfer',
                'description' => 'Transfer via Bank Central Asia',
                'config' => [
                    'account_name' => 'THRIFT SHOP',
                    'account_number' => '1234567890'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Bank Mandiri',
                'code' => 'mandiri',
                'type' => 'bank_transfer',
                'description' => 'Transfer via Bank Mandiri',
                'config' => [
                    'account_name' => 'THRIFT SHOP',
                    'account_number' => '0987654321'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Bank Negara Indonesia (BNI)',
                'code' => 'bni',
                'type' => 'bank_transfer',
                'description' => 'Transfer via Bank Negara Indonesia',
                'config' => [
                    'account_name' => 'THRIFT SHOP',
                    'account_number' => '1122334455'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Bank Rakyat Indonesia (BRI)',
                'code' => 'bri',
                'type' => 'bank_transfer',
                'description' => 'Transfer via Bank Rakyat Indonesia',
                'config' => [
                    'account_name' => 'THRIFT SHOP',
                    'account_number' => '5566778899'
                ],
                'is_active' => true
            ],
            [
                'name' => 'GoPay',
                'code' => 'gopay',
                'type' => 'e_wallet',
                'description' => 'Payment via GoPay',
                'config' => [
                    'account_name' => 'THRIFT SHOP',
                    'account_number' => '08998083333'
                ],
                'is_active' => true
            ],
            [
                'name' => 'OVO',
                'code' => 'ovo',
                'type' => 'e_wallet',
                'description' => 'Payment via OVO',
                'config' => [
                    'account_name' => 'THRIFT SHOP',
                    'account_number' => '08998083333'
                ],
                'is_active' => true
            ],
            [
                'name' => 'DANA',
                'code' => 'dana',
                'type' => 'e_wallet',
                'description' => 'Payment via DANA',
                'config' => [
                    'account_name' => 'THRIFT SHOP',
                    'account_number' => '08998083333'
                ],
                'is_active' => true
            ],
            [
                'name' => 'LinkAja',
                'code' => 'linkaja',
                'type' => 'e_wallet',
                'description' => 'Payment via LinkAja',
                'config' => [
                    'account_name' => 'THRIFT SHOP',
                    'account_number' => '08998083333'
                ],
                'is_active' => true
            ]
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}