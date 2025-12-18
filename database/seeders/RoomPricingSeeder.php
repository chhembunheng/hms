<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomPricing;
use App\Models\RoomType;

class RoomPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = RoomType::all();

        $pricingData = [
            [
                'price' => 50.00,
                'currency' => 'USD',
                'effective_from' => now()->format('Y-m-d'),
                'is_active' => true,
            ],
            [
                'price' => 80.00,
                'currency' => 'USD',
                'effective_from' => now()->format('Y-m-d'),
                'is_active' => true,
            ],
            [
                'price' => 100.00,
                'currency' => 'USD',
                'effective_from' => now()->format('Y-m-d'),
                'is_active' => true,
            ],
            [
                'price' => 150.00,
                'currency' => 'USD',
                'effective_from' => now()->format('Y-m-d'),
                'is_active' => true,
            ],
            [
                'price' => 250.00,
                'currency' => 'USD',
                'effective_from' => now()->format('Y-m-d'),
                'is_active' => true,
            ],
            [
                'price' => 500.00,
                'currency' => 'USD',
                'effective_from' => now()->format('Y-m-d'),
                'is_active' => true,
            ],
        ];

        foreach ($roomTypes as $index => $roomType) {
            if (isset($pricingData[$index])) {
                RoomPricing::create(array_merge($pricingData[$index], [
                    'room_type_id' => $roomType->id,
                ]));
            }
        }
    }
}
