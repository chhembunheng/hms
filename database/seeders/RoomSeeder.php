<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomType;
use App\Models\RoomStatus;
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
            ['room_number' => '101', 'floor' => 1, 'room_type_id' => 1, 'status_id' => 1, 'is_active' => true],
            ['room_number' => '102', 'floor' => 1, 'room_type_id' => 2, 'status_id' => 1, 'is_active' => true],
            ['room_number' => '201', 'floor' => 2, 'room_type_id' => 3, 'status_id' => 2, 'is_active' => true],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
