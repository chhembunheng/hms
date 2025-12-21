<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(SystemConfigurationSeeder::class);
        $this->call(ExchangeRateSeeder::class);
        $this->call(RoomTypeSeeder::class);
        $this->call(RoomStatusSeeder::class);
        $this->call(RoomPricingSeeder::class);
        $this->call(FloorSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(CheckInSeeder::class);
    }
}
