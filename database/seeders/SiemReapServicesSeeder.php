<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class SiemReapServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'code' => 'TOUR-ANGKOR-SUNRISE',
                'name_en' => 'Angkor Wat Sunrise Tour (Small Circuit)',
                'name_kh' => 'ទស្សនាថ្ងៃរះអង្គរវត្ត (វង់តូច)',
                'category' => 'tour',
                'price' => 25.00,
                'unit' => 'trip',
                'description' => 'Early morning Angkor Wat sunrise, Bayon, Ta Prohm (Tomb Raider temple) with remork/tuk-tuk driver.',
            ],
            [
                'code' => 'TOUR-ANGKOR-GRAND',
                'name_en' => 'Angkor Grand Circuit & Banteay Srei',
                'name_kh' => 'ទស្សនាប្រាសាទវង់ធំ និងបន្ទាយស្រី',
                'category' => 'tour',
                'price' => 35.00,
                'unit' => 'trip',
                'description' => 'Preah Khan, Neak Pean, Ta Som, East Mebon, Pre Rup, and the pink sandstone of Banteay Srei.',
            ],
            [
                'code' => 'TOUR-KULEN-WATERFALL',
                'name_en' => 'Phnom Kulen Waterfall & 1000 Lingas',
                'name_kh' => 'ដំណើរកម្សាន្តភ្នំគូលែន និងទឹកធ្លាក់',
                'category' => 'tour',
                'price' => 45.00,
                'unit' => 'trip',
                'description' => 'Sacred mountain day tour, Reclining Buddha, 1000 Lingas riverbed, and Kulen waterfall swimming.',
            ],
            [
                'code' => 'TOUR-TONLE-SAP',
                'name_en' => 'Tonle Sap Floating Village (Kampong Phluk)',
                'name_kh' => 'ទស្សនាភូមិបណ្តែតទឹកកំពង់ភ្លុក (ទន្លេសាប)',
                'category' => 'tour',
                'price' => 30.00,
                'unit' => 'trip',
                'description' => 'Scenic boat trip through stilted houses, flooded mangrove forest, and lake sunset.',
            ],
            [
                'code' => 'TRANS-SAI-VAN-PICKUP',
                'name_en' => 'SAI Airport Pickup (Private AC Van)',
                'name_kh' => 'សេវាទទួលពីព្រលានយន្តហោះសៀមរាបអង្គរ (រថយន្តវ៉ែន)',
                'category' => 'transport',
                'price' => 35.00,
                'unit' => 'trip',
                'description' => 'Private AC minivan transfer from new Siem Reap–Angkor Airport (SAI) to hotel (~45 km).',
            ],
            [
                'code' => 'TRANS-SAI-VAN-DROPOFF',
                'name_en' => 'SAI Airport Drop-off (Private AC Van)',
                'name_kh' => 'សេវាជូនទៅព្រលានយន្តហោះសៀមរាបអង្គរ (រថយន្តវ៉ែន)',
                'category' => 'transport',
                'price' => 35.00,
                'unit' => 'trip',
                'description' => 'Private AC minivan transfer from hotel to new Siem Reap–Angkor Airport (SAI).',
            ],
            [
                'code' => 'TRANS-SAI-TUKTUK',
                'name_en' => 'Airport Transfer (Traditional Remork / Tuk-Tuk)',
                'name_kh' => 'សេវាធ្វើដំណើរតាមរ៉ឺម៉កកង់បី (ទូកទូក)',
                'category' => 'transport',
                'price' => 20.00,
                'unit' => 'trip',
                'description' => 'Authentic Siem Reap remork ride for up to 3 passengers.',
            ],
            [
                'code' => 'LAUNDRY-STANDARD',
                'name_en' => 'Laundry Service (Wash, Dry & Fold)',
                'name_kh' => 'សេវាបោកអ៊ុតសម្លៀកបំពាក់ (គិតជាគីឡូ)',
                'category' => 'laundry',
                'price' => 2.00,
                'unit' => 'kg',
                'description' => 'Standard next-day laundry wash, dry and fold.',
            ],
            [
                'code' => 'LAUNDRY-EXPRESS',
                'name_en' => 'Express Laundry (Same-Day Return)',
                'name_kh' => 'សេវាបោកអ៊ុតបន្ទាន់ (យកក្នុងថ្ងៃ)',
                'category' => 'laundry',
                'price' => 3.50,
                'unit' => 'kg',
                'description' => 'Fast 6-hour turnaround laundry service.',
            ],
            [
                'code' => 'SPA-KHMER-MASSAGE',
                'name_en' => 'Traditional Khmer Herbal Massage (60 min)',
                'name_kh' => 'ម៉ាស្សាបែបបុរាណខ្មែរ (៦០ នាទី)',
                'category' => 'spa',
                'price' => 15.00,
                'unit' => 'hour',
                'description' => 'Relieving tension and muscle fatigue after full-day Angkor temple explorations.',
            ],
            [
                'code' => 'SPA-AROMA-OIL',
                'name_en' => 'Aromatherapy Oil Massage (60 min)',
                'name_kh' => 'ម៉ាស្សាប្រេងក្រអូប (៦០ នាទី)',
                'category' => 'spa',
                'price' => 20.00,
                'unit' => 'hour',
                'description' => 'Relaxing full-body essential oil massage.',
            ],
            [
                'code' => 'ROOM-EXTRA-BED',
                'name_en' => 'Extra Bed with Breakfast',
                'name_kh' => 'គ្រែបន្ថែមរួមទាំងអាហារពេលព្រឹក',
                'category' => 'other',
                'price' => 15.00,
                'unit' => 'night',
                'description' => 'Rollaway single bed setup in room with daily buffet breakfast included.',
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['code' => $service['code']],
                $service
            );
        }
    }
}
