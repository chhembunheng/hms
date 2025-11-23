<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\Navigation;
use Illuminate\Support\Facades\File;
use App\Models\Frontend\NavigationTranslation;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path("seeders/data/frontend/navigations.json");

        if (!file_exists($jsonPath)) {
            $this->command->warn("FAQ JSON file not found at: {$jsonPath}");
            return;
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        NavigationTranslation::truncate();
        Navigation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->warn("🧹 Existing FAQ data truncated.");
        $navigations = json_decode(file_get_contents($jsonPath), true);

        $this->createNavigations($navigations, null);
    }

    private function createNavigations($navigations, $parentId = null): void
    {
        foreach ($navigations as $navigation) {
            $navigationModel = Navigation::updateOrCreate(
                [
                    'parent_id' => $parentId,
                    'slug' => $navigation['slug'] ?? null,
                ],
                [
                    'parent_id' => $parentId,
                    'slug' => $navigation['slug'] ?? null,
                    'icon' => $navigation['icon'],
                    'url' => $navigation['url'] ?? null,
                    'sort' => $navigation['sort'],
                ]
            );

            foreach ($navigation['transactions'] as $navigationTransaction) {
                NavigationTranslation::updateOrCreate(
                    [
                        'navigation_id' => $navigationModel->id,
                        'locale' => $navigationTransaction['locale'],
                    ],
                    [
                        'name' => $navigationTransaction['name'],
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]
                );
            }
            // Recursively create child navigations
            if (isset($navigation['children']) && !empty($navigation['children'])) {
                $this->createNavigations($navigation['children'], $navigationModel->id);
            }
        }
    }
}
