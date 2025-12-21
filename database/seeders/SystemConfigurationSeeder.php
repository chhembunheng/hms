<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\SystemConfiguration;

class SystemConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default system configuration if it doesn't exist
        SystemConfiguration::firstOrCreate(
            [], // Empty where clause to check if any record exists
            [
                'hotel_name_en' => 'Hotel Management System',
                'hotel_name_kh' => 'ប្រព័ន្ធគ្រប់គ្រងសណ្ឋាគារ',
                'location_en' => 'Phnom Penh, Cambodia',
                'location_kh' => 'ភ្នំពេញ ប្រទេសកម្ពុជា',
                'phone_number' => '+855 12 345 678',
                'email' => 'info@hotel.com',
                'watermark_title' => 'HMS',
                'system_title' => 'Hotel Management System',
            ]
        );
    }
}
