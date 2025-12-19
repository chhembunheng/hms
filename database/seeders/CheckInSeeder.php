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
                'number_of_guests' => 2,
                'check_in_date' => now()->format('Y-m-d'),
                'check_out_date' => now()->addDays(3)->format('Y-m-d'),
                'total_amount' => 450.00,
                'paid_amount' => 225.00,
                'status' => 'confirmed',
                'notes' => 'Business trip guest',
            ],
            [
                'room_id' => $availableRooms->skip(1)->first()?->id ?? $availableRooms->first()->id,
                'guest_name' => 'Sokun Doe',
                'guest_email' => 'sokun.doe@example.com',
                'guest_phone' => '+855-12-345-678',
                'guest_type' => 'national',
                'guest_national_id' => '123456789',
                'number_of_guests' => 1,
                'check_in_date' => now()->subDays(1)->format('Y-m-d'),
                'check_out_date' => now()->addDays(2)->format('Y-m-d'),
                'total_amount' => 200.00,
                'paid_amount' => 200.00,
                'status' => 'checked_in',
                'notes' => 'Regular guest',
                'actual_check_in_at' => now()->subDays(1)->setTime(14, 30),
            ],
            [
                'room_id' => $availableRooms->skip(2)->first()?->id ?? $availableRooms->first()->id,
                'guest_name' => 'Maria Garcia',
                'guest_email' => 'maria.garcia@example.com',
                'guest_phone' => '+34-666-123-456',
                'guest_type' => 'international',
                'guest_passport' => 'E987654321',
                'guest_country' => 'Spain',
                'number_of_guests' => 3,
                'check_in_date' => now()->subDays(5)->format('Y-m-d'),
                'check_out_date' => now()->subDays(2)->format('Y-m-d'),
                'total_amount' => 600.00,
                'paid_amount' => 600.00,
                'status' => 'checked_out',
                'notes' => 'Family vacation',
                'actual_check_in_at' => now()->subDays(5)->setTime(15, 0),
                'actual_check_out_at' => now()->subDays(2)->setTime(11, 0),
            ],
        ];

        foreach ($checkIns as $checkInData) {
            // Skip if room is not available
            if (!isset($checkInData['room_id'])) {
                continue;
            }

            CheckIn::create($checkInData);
        }
    }
}
