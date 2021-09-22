<?php

namespace Database\Factories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class PurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tokenKey = Str::random(600);
        $token = hash('sha256', $tokenKey) . mt_rand(1000, 9999);

        return [
            'device_id' => mt_rand(1, 1000),
            'receipt' => $token,
            'expire-date' => $this->faker->dateTimeBetween('now', '+10 month'),
            'status' => 1
        ];
    }
}
