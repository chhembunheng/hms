<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Floor;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Note: Room types are seeded in RoomTypeSeeder
        // Note: Room statuses are seeded in RoomStatusSeeder

        // Seed sample rooms
        $rooms = [
            // Floor 1
            ['room_number' => '101', 'floor_id' => 1, 'room_type_id' => 1, 'status_id' => 1, 'is_active' => true],
            ['room_number' => '102', 'floor_id' => 1, 'room_type_id' => 2, 'status_id' => 1, 'is_active' => true],
            ['room_number' => '103', 'floor_id' => 1, 'room_type_id' => 1, 'status_id' => 2, 'is_active' => true],
            ['room_number' => '104', 'floor_id' => 1, 'room_type_id' => 3, 'status_id' => 1, 'is_active' => true],
            ['room_number' => '105', 'floor_id' => 1, 'room_type_id' => 2, 'status_id' => 3, 'is_active' => true],

            // Floor 2
            ['room_number' => '201', 'floor_id' => 2, 'room_type_id' => 3, 'status_id' => 1, 'is_active' => true],
            ['room_number' => '202', 'floor_id' => 2, 'room_type_id' => 1, 'status_id' => 2, 'is_active' => true],
            ['room_number' => '203', 'floor_id' => 2, 'room_type_id' => 2, 'status_id' => 1, 'is_active' => true],
            ['room_number' => '204', 'floor_id' => 2, 'room_type_id' => 3, 'status_id' => 1, 'is_active' => true],
            ['room_number' => '205', 'floor_id' => 2, 'room_type_id' => 1, 'status_id' => 3, 'is_active' => true],

            // Floor 3
            ['room_number' => '301', 'floor_id' => 3, 'room_type_id' => 2, 'status_id' => 1, 'is_active' => true],
            ['room_number' => '302', 'floor_id' => 3, 'room_type_id' => 3, 'status_id' => 1, 'is_active' => true],
            ['room_number' => '303', 'floor_id' => 3, 'room_type_id' => 1, 'status_id' => 2, 'is_active' => true],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
