<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Floor;

class FloorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $floors = [
            ['floor_number' => 1, 'name_en' => 'Ground Floor', 'name_kh' => 'ជាន់ទី១', 'is_active' => true],
            ['floor_number' => 2, 'name_en' => 'First Floor', 'name_kh' => 'ជាន់ទី២', 'is_active' => true],
            ['floor_number' => 3, 'name_en' => 'Second Floor', 'name_kh' => 'ជាន់ទី៣', 'is_active' => true],
            ['floor_number' => 4, 'name_en' => 'Third Floor', 'name_kh' => 'ជាន់ទី៤', 'is_active' => true],
            ['floor_number' => 5, 'name_en' => 'Fourth Floor', 'name_kh' => 'ជាន់ទី៥', 'is_active' => true],
        ];

        foreach ($floors as $floor) {
            Floor::create($floor);
        }
    }
}
