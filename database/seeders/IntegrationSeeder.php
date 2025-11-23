<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Frontend\Integration;
use App\Models\Frontend\IntegrationTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class IntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/frontend/integrations.json');
        if (! File::exists($path)) {
            $this->command?->warn("IntegrationSeeder: integrations.json not found at $path");
            return;
        }

        $root = json_decode(File::get($path), true);
        if (! is_array($root)) {
            $this->command?->error('IntegrationSeeder: Invalid JSON structure.');
            return;
        }

        DB::transaction(function () use ($root) {
            foreach ($root as $categoryIndex => $category) {
                // Each category may contain an "integrations" array (actual items)
                $items = $category['integrations'] ?? [];
                if (! is_array($items) || empty($items)) {
                    continue; // skip categories without integrations list
                }

                foreach ($items as $itemIndex => $item) {
                    // Base integration record keyed by URL (acts as unique identifier)
                    $url = $item['url'] ?? null;
                    if (! $url) {
                        $this->command?->warn("IntegrationSeeder: Skipping item at category {$categoryIndex} index {$itemIndex} (missing url)");
                        continue;
                    }
                    $logo = $item['image'] ?? $item['icon'] ?? null;
                    $integrationModel = Integration::query()->firstOrNew(['url' => $url]);
                    $integrationModel->fill([
                        'logo' => $logo,
                        'is_active' => true,
                        'created_by' => $integrationModel->exists ? $integrationModel->created_by : 1,
                        'updated_by' => 1,
                    ]);
                    $integrationModel->save();

                    // Handle translations (key might be 'transactions' or fallback 'translations')
                    $translations = $item['transactions'] ?? $item['translations'] ?? [];
                    if (is_array($translations)) {
                        foreach ($translations as $tIndex => $t) {
                            $locale = $t['locale'] ?? 'en';
                            $name = $t['name'] ?? ($item['slug'] ?? 'Integration');
                            $description = $t['description'] ?? null;
                            $content = $t['content'] ?? null;

                            $translation = IntegrationTranslation::query()->firstOrNew([
                                'integration_id' => $integrationModel->id,
                                'locale' => $locale,
                            ]);
                            $translation->fill([
                                'name' => $name,
                                'description' => $description,
                                'content' => $content,
                                'created_by' => $translation->exists ? $translation->created_by : 1,
                                'updated_by' => 1,
                            ]);
                            $translation->save();
                        }
                    }
                }
            }
        });
    }
}
