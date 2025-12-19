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
                'name_en' => 'Available',
                'name_kh' => 'ទំនេរ',
                'description' => 'Room is available for booking',
                'color' => '#28a745',
                'is_active' => true,
            ],
            [
                'name_en' => 'Occupied',
                'name_kh' => 'មានអ្នកស្នាក់នៅ',
                'description' => 'Room is currently occupied',
                'color' => '#dc3545',
                'is_active' => true,
            ],
            [
                'name_en' => 'Cleaning',
                'name_kh' => 'កំពុងសម្អាត',
                'description' => 'Room is being cleaned',
                'color' => '#ffc107',
                'is_active' => true,
            ],
            [
                'name_en' => 'Maintenance',
                'name_kh' => 'កំពុងជួសជុល',
                'description' => 'Room is under maintenance',
                'color' => '#6c757d',
                'is_active' => true,
            ],
            [
                'name_en' => 'Out of Order',
                'name_kh' => 'មិនអាចប្រើបាន',
                'description' => 'Room is out of service',
                'color' => '#dc3545',
                'is_active' => true,
            ],
            [
                'name_en' => 'Reserved',
                'name_kh' => 'បានបម្រុងទុក',
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
