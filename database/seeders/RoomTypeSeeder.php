<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Standard Single',
                'description' => 'Single room with basic amenities',
                'is_active' => true,
            ],
            [
                'name' => 'Standard Double',
                'description' => 'Double room with basic amenities',
                'is_active' => true,
            ],
            [
                'name' => 'Deluxe Single',
                'description' => 'Single room with premium amenities',
                'is_active' => true,
            ],
            [
                'name' => 'Deluxe Double',
                'description' => 'Double room with premium amenities',
                'is_active' => true,
            ],
            [
                'name' => 'Suite',
                'description' => 'Luxury suite with multiple rooms',
                'is_active' => true,
            ],
            [
                'name' => 'Presidential Suite',
                'description' => 'Ultimate luxury suite',
                'is_active' => true,
            ],
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::create($roomType);
        }
    }
}
