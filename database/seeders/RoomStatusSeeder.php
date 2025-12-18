<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomStatus;

class RoomStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomStatuses = [
            [
                'name' => 'Available',
                'description' => 'Room is available for booking',
                'color' => '#28a745',
                'is_active' => true,
            ],
            [
                'name' => 'Occupied',
                'description' => 'Room is currently occupied',
                'color' => '#dc3545',
                'is_active' => true,
            ],
            [
                'name' => 'Cleaning',
                'description' => 'Room is being cleaned',
                'color' => '#ffc107',
                'is_active' => true,
            ],
            [
                'name' => 'Maintenance',
                'description' => 'Room is under maintenance',
                'color' => '#6c757d',
                'is_active' => true,
            ],
            [
                'name' => 'Out of Order',
                'description' => 'Room is out of service',
                'color' => '#dc3545',
                'is_active' => true,
            ],
            [
                'name' => 'Reserved',
                'description' => 'Room is reserved',
                'color' => '#007bff',
                'is_active' => true,
            ],
        ];

        foreach ($roomStatuses as $roomStatus) {
            RoomStatus::create($roomStatus);
        }
    }
}
