<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected $baseUrl;
    protected $apiKey;
    protected $accountType;

    public function __construct()
    {
        $this->baseUrl = config('services.rajaongkir.base_url');
        $this->apiKey = config('services.rajaongkir.api_key');
        $this->accountType = config('services.rajaongkir.account_type', 'starter');
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        return Cache::remember('rajaongkir_provinces', 60 * 24, function () {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get("{$this->baseUrl}/province");

            return $response->json()['rajaongkir']['results'] ?? [];
        });
    }

    /**
     * Get cities by province
     */
    public function getCities($provinceId)
    {
        $cacheKey = "rajaongkir_cities_{$provinceId}";

        return Cache::remember($cacheKey, 60 * 24, function () use ($provinceId) {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get("{$this->baseUrl}/city", [
                'province' => $provinceId
            ]);

            return $response->json()['rajaongkir']['results'] ?? [];
        });
    }

    /**
     * Get districts by city
     */
    public function getDistricts($cityId)
    {
        $cacheKey = "rajaongkir_districts_{$cityId}";

        return Cache::remember($cacheKey, 60 * 24, function () use ($cityId) {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get("{$this->baseUrl}/subdistrict", [
                'city' => $cityId
            ]);

            return $response->json()['rajaongkir']['results'] ?? [];
        });
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShippingCost($origin, $destination, $weight, $courier)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->post("{$this->baseUrl}/cost", [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier
        ]);

        $result = $response->json()['rajaongkir']['results'] ?? [];

        if (empty($result)) {
            return [];
        }

        $costs = [];
        foreach ($result[0]['costs'] as $cost) {
            $costs[] = [
                'service' => $cost['service'],
                'description' => $cost['description'],
                'cost' => $cost['cost'][0]['value'],
                'etd' => $cost['cost'][0]['etd'] ?? null
            ];
        }

        return [
            'courier' => $result[0]['code'],
            'courier_name' => $result[0]['name'],
            'costs' => $costs
        ];
    }

    /**
     * Get available couriers
     */
    public function getAvailableCouriers()
    {
        return [
            'jne' => 'JNE',
            'pos' => 'POS Indonesia',
            'tiki' => 'TIKI'
        ];
    }
}