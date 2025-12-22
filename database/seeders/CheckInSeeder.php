<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CheckIn;
use App\Models\Room;

class CheckInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get available rooms
        $availableRooms = Room::whereHas('status', function($query) {
            $query->where('name_en', 'Available');
        })->with('roomType')->get();

        if ($availableRooms->isEmpty()) {
            return;
        }

        $checkIns = [
            [
                'room_id' => $availableRooms->first()->id,
                'guest_name' => 'John Smith',
                'guest_email' => 'john.smith@example.com',
                'guest_phone' => '+1-555-0123',
                'guest_type' => 'international',
                'guest_passport' => 'P123456789',
                'guest_country' => 'United States',
                'total_guests' => 2,
                'billing_type' => 'night',
                'check_in_date' => now()->format('Y-m-d'),
                'check_out_date' => now()->addDays(3)->format('Y-m-d'),
                'total_amount' => 44.00,
                'paid_amount' => 44.00,
                'status' => 'checked_in',
                'notes' => 'Business trip guest',
            ],
            [
                'room_id' => $availableRooms->skip(1)->first()?->id ?? $availableRooms->first()->id,
                'guest_name' => 'Maria Garcia',
                'guest_email' => 'maria.garcia@example.com',
                'guest_phone' => '+34-666-123-456',
                'guest_type' => 'international',
                'guest_passport' => 'E987654321',
                'guest_country' => 'Spain',
                'total_guests' => 3,
                'billing_type' => 'night',
                'check_in_date' => now()->subDays(5)->format('Y-m-d'),
                'check_out_date' => now()->subDays(2)->format('Y-m-d'),
                'total_amount' => 25,
                'paid_amount' => 75,
                'status' => 'checked_out',
                'notes' => 'Family vacation',
                'actual_check_in_at' => now()->subDays(5)->setTime(15, 0),
                'actual_check_out_at' => now()->subDays(2)->setTime(11, 0),
            ],
        ];

        foreach ($checkIns as $checkInData) {
            if (!isset($checkInData['room_id'])) {
                continue;
            }

            CheckIn::create($checkInData);
        }
    }
}
