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
                'night_price' => 50.00,
                'three_hour_price' => 25.00,
            ],
            [
                'night_price' => 80.00,
                'three_hour_price' => 40.00,
            ],
            [
                'night_price' => 100.00,
                'three_hour_price' => 50.00,
            ],
            [
                'night_price' => 150.00,
                'three_hour_price' => 75.00,
            ],
            [
                'night_price' => 250.00,
                'three_hour_price' => 125.00,
            ],
            [
                'night_price' => 500.00,
                'three_hour_price' => 250.00,
            ],
        ];

        foreach ($roomTypes as $index => $roomType) {
            if (isset($pricingData[$index])) {
                // Create per night pricing
                RoomPricing::create([
                    'room_type_id' => $roomType->id,
                    'price' => $pricingData[$index]['night_price'],
                    'pricing_type' => 'night',
                    'currency' => 'USD',
                    'effective_from' => now()->format('Y-m-d'),
                    'is_active' => true,
                ]);

                // Create 3-hour pricing
                RoomPricing::create([
                    'room_type_id' => $roomType->id,
                    'price' => $pricingData[$index]['three_hour_price'],
                    'pricing_type' => '3_hours',
                    'currency' => 'USD',
                    'effective_from' => now()->format('Y-m-d'),
                    'is_active' => true,
                ]);
            }
        }
    }
}
