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
                'name_en' => 'Single Room (Fan)',
                'name_kh' => 'បន្ទប់ឯកត្តា (កង្ហារ)',
                'description' => 'Single room with fan, suitable for one person, affordable price',
                'is_active' => true,
                'max_guests' => 1,
            ],
            [
                'name_en' => 'Single Room (AC)',
                'name_kh' => 'បន្ទប់ឯកត្តា (ម៉ាស៊ីនត្រជាក់)',
                'description' => 'Single room with air conditioner, suitable for one guest',
                'is_active' => true,
                'max_guests' => 1,
            ],
            [
                'name_en' => 'Double Room (AC)',
                'name_kh' => 'បន្ទប់គ្រែពីរ (ម៉ាស៊ីនត្រជាក់)',
                'description' => 'Large double room with air conditioner, suitable for couples',
                'is_active' => true,
                'max_guests' => 2,
            ],
            [
                'name_en' => 'Twin Room (AC)',
                'name_kh' => 'បន្ទប់គ្រែពីរ (Twin – ម៉ាស៊ីនត្រជាក់)',
                'description' => 'Two small beds room with air conditioner, suitable for staying with friends or classmates',
                'is_active' => true,
                'max_guests' => 2,
            ],
            [
                'name_en' => 'Twin Room (Fan)',
                'name_kh' => 'បន្ទប់គ្រែពីរ (Twin – កង្ហារ)',
                'description' => 'Two small beds room with fan, suitable for students or close friends',
                'is_active' => true,
                'max_guests' => 2,
            ],
            [
                'name_en' => 'Dormitory Room',
                'name_kh' => 'បន្ទប់គេងរួម (Dormitory)',
                'description' => 'Shared dormitory room, suitable for staying with classmates or groups',
                'is_active' => true,
                'max_guests' => 4,
            ],
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::updateOrCreate(
                ['name_en' => $roomType['name_en']],
                $roomType
            );
        }
    }
}
