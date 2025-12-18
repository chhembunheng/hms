<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ExchangeRate::create([
            'from_currency' => 'USD',
            'to_currency' => 'KHR',
            'rate' => 4100.00,
            'effective_date' => now()->toDateString(),
            'is_active' => true,
        ]);
    }
}
