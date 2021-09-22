<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AppSeeder::class);
        $this->call(DeviceSeeder::class);
        $this->call(PurchaseSeeder::class);
        // \App\Models\User::factory(10)->create();
    }
}
