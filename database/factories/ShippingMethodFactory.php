<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingMethodFactory extends Factory
{
    protected static $methodIndex = 0;
    protected static $methods = [
        ['name' => 'JNE Regular', 'code' => 'jne'],
        ['name' => 'JNE Express', 'code' => 'jne_express'],
        ['name' => 'SiCepat Regular', 'code' => 'sicepat'],
        ['name' => 'SiCepat Express', 'code' => 'sicepat_express'],
        ['name' => 'GoSend', 'code' => 'gosend'],
        ['name' => 'AnterAja', 'code' => 'anteraja'],
        ['name' => 'Ninja Express', 'code' => 'ninja'],
        ['name' => 'Pos Indonesia', 'code' => 'pos'],
        ['name' => 'TIKI', 'code' => 'tiki'],
        ['name' => 'J&T Express', 'code' => 'jnt'],
    ];

    public function definition(): array
    {
        // Avoid the unique() constraint by using a static index
        $methodIndex = self::$methodIndex % count(self::$methods);
        $method = self::$methods[$methodIndex];
        self::$methodIndex++;

        return [
            'name' => $method['name'],
            'code' => $method['code'],
            'is_active' => true,
        ];
    }
}