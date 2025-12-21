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
            15.00, // Single Room (Fan)
            18.00, // Single Room (AC)
            22.00, // Double Room (AC)
            25.00, // Twin Room (AC)
            20.00, // Twin Room (Fan)
            35.00, // Dormitory Room
        ];

        foreach ($roomTypes as $index => $roomType) {
            if (isset($pricingData[$index])) {
                // Create per night pricing only
                RoomPricing::create([
                    'room_type_id' => $roomType->id,
                    'price' => $pricingData[$index],
                    'pricing_type' => 'night',
                    'currency' => 'USD',
                    'effective_from' => now()->format('Y-m-d'),
                    'is_active' => true,
                ]);
            }
        }
    }
}
