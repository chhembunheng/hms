<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\Menu;
use App\Models\Settings\MenuTranslation;
use App\Models\Settings\Permission;
use App\Models\Settings\PermissionTranslation;
use Illuminate\Support\Facades\File;

class SiemReapMenusSeeder extends Seeder
{
    public function run(): void
    {
        $menusPath = database_path('seeders/data/backend/menus.json');
        $menus = json_decode(File::get($menusPath), true) ?? [];

        // 1. Create or Find "Services & Tours" main menu
        $servicesMenu = Menu::updateOrCreate(
            ['icon' => 'fa-van-shuttle', 'parent_id' => null],
            ['sort' => 6, 'route' => null]
        );

        MenuTranslation::updateOrCreate(
            ['menu_id' => $servicesMenu->id, 'locale' => 'en'],
            ['name' => 'Services & Tours', 'description' => 'Siem Reap airport transfers, Angkor excursions, laundry and extra amenities', 'created_by' => 1]
        );
        MenuTranslation::updateOrCreate(
            ['menu_id' => $servicesMenu->id, 'locale' => 'km'],
            ['name' => 'សេវាកម្ម & ដំណើរកម្សាន្ត', 'description' => 'ការដឹកជញ្ជូនព្រលានយន្តហោះសៀមរាប ដំណើរកម្សាន្តអង្គរ និងបោកអ៊ុត', 'created_by' => 1]
        );

        // Child 1: Airport Transfers
        $transferMenu = Menu::updateOrCreate(
            ['icon' => 'fa-plane-arrival', 'parent_id' => $servicesMenu->id],
            ['sort' => 1, 'route' => 'services.transfers.index']
        );
        MenuTranslation::updateOrCreate(
            ['menu_id' => $transferMenu->id, 'locale' => 'en'],
            ['name' => 'SAI Airport Transfers', 'description' => 'Siem Reap Angkor Airport transfers and transport desk', 'created_by' => 1]
        );
        MenuTranslation::updateOrCreate(
            ['menu_id' => $transferMenu->id, 'locale' => 'km'],
            ['name' => 'ដឹកជញ្ជូនព្រលានយន្តហោះ SAI', 'description' => 'ការកក់ឡាន និងរ៉ឺម៉កកង់បីទៅមកព្រលានយន្តហោះ', 'created_by' => 1]
        );
        $p1 = Permission::updateOrCreate(
            ['action_route' => 'services.transfers.index', 'menu_id' => $transferMenu->id],
            ['action' => 'index', 'icon' => 'fa-list', 'target' => 'self', 'sort' => 0]
        );
        PermissionTranslation::updateOrCreate(['permission_id' => $p1->id, 'locale' => 'en'], ['name' => 'View', 'created_by' => 1]);
        PermissionTranslation::updateOrCreate(['permission_id' => $p1->id, 'locale' => 'km'], ['name' => 'មើល', 'created_by' => 1]);

        // Child 2: Room Folio Charges
        $roomChargeMenu = Menu::updateOrCreate(
            ['icon' => 'fa-file-invoice-dollar', 'parent_id' => $servicesMenu->id],
            ['sort' => 2, 'route' => 'services.room-services.index']
        );
        MenuTranslation::updateOrCreate(
            ['menu_id' => $roomChargeMenu->id, 'locale' => 'en'],
            ['name' => 'Room Folio Charges', 'description' => 'Post tour, laundry, spa charges to staying rooms', 'created_by' => 1]
        );
        MenuTranslation::updateOrCreate(
            ['menu_id' => $roomChargeMenu->id, 'locale' => 'km'],
            ['name' => 'គិតប្រាក់សេវាបន្ទប់', 'description' => 'កត់ត្រាសេវាដំណើរកម្សាន្ត បោកអ៊ុត ម៉ាស្សា ចូលវិក្កយបត្របន្ទប់', 'created_by' => 1]
        );
        $p2 = Permission::updateOrCreate(
            ['action_route' => 'services.room-services.index', 'menu_id' => $roomChargeMenu->id],
            ['action' => 'index', 'icon' => 'fa-list', 'target' => 'self', 'sort' => 0]
        );
        PermissionTranslation::updateOrCreate(['permission_id' => $p2->id, 'locale' => 'en'], ['name' => 'View', 'created_by' => 1]);
        PermissionTranslation::updateOrCreate(['permission_id' => $p2->id, 'locale' => 'km'], ['name' => 'មើល', 'created_by' => 1]);

        // Child 3: Tours & Service Catalog
        $catalogMenu = Menu::updateOrCreate(
            ['icon' => 'fa-compass', 'parent_id' => $servicesMenu->id],
            ['sort' => 3, 'route' => 'services.catalog.index']
        );
        MenuTranslation::updateOrCreate(
            ['menu_id' => $catalogMenu->id, 'locale' => 'en'],
            ['name' => 'Tours & Service Catalog', 'description' => 'Manage tours, excursions, and price list', 'created_by' => 1]
        );
        MenuTranslation::updateOrCreate(
            ['menu_id' => $catalogMenu->id, 'locale' => 'km'],
            ['name' => 'តារាងសេវាកម្ម & ដំណើរកម្សាន្ត', 'description' => 'គ្រប់គ្រងកញ្ចប់ដំណើរកម្សាន្ត និងតម្លៃសេវា', 'created_by' => 1]
        );
        $p3 = Permission::updateOrCreate(
            ['action_route' => 'services.catalog.index', 'menu_id' => $catalogMenu->id],
            ['action' => 'index', 'icon' => 'fa-list', 'target' => 'self', 'sort' => 0]
        );
        PermissionTranslation::updateOrCreate(['permission_id' => $p3->id, 'locale' => 'en'], ['name' => 'View', 'created_by' => 1]);
        PermissionTranslation::updateOrCreate(['permission_id' => $p3->id, 'locale' => 'km'], ['name' => 'មើល', 'created_by' => 1]);

        // 2. Find Reports Menu and add FPCS Report
        $reportsMenu = Menu::where('icon', 'fa-file-lines')->orWhere('sort', 10)->whereNull('parent_id')->first();
        if ($reportsMenu) {
            $fpcsMenu = Menu::updateOrCreate(
                ['icon' => 'fa-passport', 'parent_id' => $reportsMenu->id],
                ['sort' => 5, 'route' => 'reports.fpcs.index']
            );
            MenuTranslation::updateOrCreate(
                ['menu_id' => $fpcsMenu->id, 'locale' => 'en'],
                ['name' => 'FPCS Police Report', 'description' => 'Foreigners Present in Cambodia System (FPCS) report for immigration & tourist police', 'created_by' => 1]
            );
            MenuTranslation::updateOrCreate(
                ['menu_id' => $fpcsMenu->id, 'locale' => 'km'],
                ['name' => 'របាយការណ៍ FPCS អន្តោប្រវេសន៍', 'description' => 'របាយការណ៍ជនបរទេសស្នាក់នៅកម្ពុជាសម្រាប់ប៉ូលិសទេសចរណ៍', 'created_by' => 1]
            );
            $p4 = Permission::updateOrCreate(
                ['action_route' => 'reports.fpcs.index', 'menu_id' => $fpcsMenu->id],
                ['action' => 'index', 'icon' => 'fa-list', 'target' => 'self', 'sort' => 0]
            );
            PermissionTranslation::updateOrCreate(['permission_id' => $p4->id, 'locale' => 'en'], ['name' => 'View', 'created_by' => 1]);
            PermissionTranslation::updateOrCreate(['permission_id' => $p4->id, 'locale' => 'km'], ['name' => 'មើល', 'created_by' => 1]);
        }

        // Clear all cached menus
        \Illuminate\Support\Facades\Cache::flush();
    }
}
