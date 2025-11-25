<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\Achievement;
use App\Models\Frontend\AchievementTranslation;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/frontend/achievements.json');

        if (! File::exists($path)) {
            $this->command?->warn("AchievementSeeder: achievements.json not found at $path (skipping)");
            return;
        }

        $data = json_decode(File::get($path), true);
        if (! is_array($data)) {
            $this->command?->error('AchievementSeeder: Invalid JSON structure.');
            return;
        }

        DB::transaction(function () use ($data) {
            foreach ($data as $index => $entry) {
                // JSON fields: id (optional), number, suffix, icon, translations[] { locale, title }
                $value = $entry['number'] ?? 0; // map 'number' to 'value'
                $icon = $entry['icon'] ?? null;
                $sort = $entry['id'] ?? ($index + 1);

                $achievement = Achievement::query()->firstOrNew([
                    'sort' => $sort,
                    'icon' => $icon,
                ]);
                // Always update numeric value & active flag
                $achievement->fill([
                    'value' => $value,
                    'is_active' => true,
                    'created_by' => $achievement->exists ? $achievement->created_by : 1,
                    'updated_by' => 1,
                ]);
                $achievement->save();

                $translations = $entry['translations'] ?? [];
                if (is_array($translations)) {
                    foreach ($translations as $t) {
                        $locale = $t['locale'] ?? 'en';
                        $title = $t['title'] ?? 'N/A';
                        $suffix = $t['suffix'] ?? '';

                        $translation = AchievementTranslation::query()->firstOrNew([
                            'achievement_id' => $achievement->id,
                            'locale' => $locale,
                        ]);
                        $translation->fill([
                            'title' => $title,
                            'suffix' => $suffix,
                            'created_by' => $translation->exists ? $translation->created_by : 1,
                            'updated_by' => 1,
                        ]);
                        $translation->save();
                    }
                }
            }
        });
    }
}
