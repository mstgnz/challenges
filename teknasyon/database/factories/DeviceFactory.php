<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tokenKey = Str::random(60) . time();
        $token = hash('sha256', $tokenKey);
        return [
            'uid' => mt_rand(100000, 999999),
            'app_id' => mt_rand(1, 10),
            'language' => Arr::random(["tr", "en", "ru", "de"]),
            'os' => Arr::random(["ios", "android"]),
            'token' => $token
        ];
    }
}
