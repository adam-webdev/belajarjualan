<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'rajaongkir' => [
        'api_key' => env('RAJAONGKIR_API_KEY'),
        'base_url' => env('RAJAONGKIR_BASE_URL', 'https://api-sandbox.collaborator.komerce.id'),
        'tariff_url' => env('RAJAONGKIR_TARIFF_URL', 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1'),
        'destination_url' => env('RAJAONGKIR_DESTINATION_URL', 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination'),
        'store' => [
            'province_id' => '9', // Jawa Barat
            'province_name' => 'Jawa Barat',
            'city_id' => '54', // Bekasi
            'city_name' => 'Bekasi',
            'district_id' => '733', // Bekasi Kota
            'district_name' => 'Bekasi Kota',
            'postal_code' => '17141',
            'address' => 'Jl. Raya Bekasi No. 123'
        ],
    ],

];
