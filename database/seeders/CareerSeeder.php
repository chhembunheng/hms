<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\Career;
use App\Models\Frontend\CareerTranslation;

class CareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/frontend/careers.json');

        if (! File::exists($path)) {
            $this->command?->warn("CareerSeeder: careers.json not found at $path (skipping)");
            return;
        }

        $data = json_decode(File::get($path), true);
        if (! is_array($data)) {
            $this->command?->error('CareerSeeder: Invalid JSON structure.');
            return;
        }

        DB::transaction(function () use ($data) {
            foreach ($data as $index => $entry) {
                if (! isset($entry['slug'])) {
                    $this->command?->warn("CareerSeeder: Skipping entry at index {$index} (missing slug)");
                    continue;
                }

                $career = Career::query()->firstOrNew(['slug' => $entry['slug']]);
                $career->fill([
                    'location' => $entry['location'] ?? null,
                    'deadline' => $entry['date'] ?? null, // map 'date' from JSON to deadline
                    'type' => str_replace([' ', '-'], '_', trim(strtolower($entry['type'] ?? 'full_time'))),
                    'level' => str_replace([' ', '-'], '_', trim(strtolower($entry['level'] ?? 'junior'))),
                    'priority' => str_replace([' ', '-'], '_', trim(strtolower($entry['priority'] ?? 'medium'))),
                    'sort' => $entry['sort'] ?? ($index + 1),
                    'is_active' => true,
                    'created_by' => $career->exists ? $career->created_by : 1,
                    'updated_by' => 1,
                ]);
                $career->save();

                $translations = $entry['translations'] ?? [];
                if (is_array($translations)) {
                    foreach ($translations as $t) {
                        $locale = $t['locale'] ?? 'en';
                        $translation = CareerTranslation::query()->firstOrNew([
                            'career_id' => $career->id,
                            'locale' => $locale,
                        ]);
                        $translation->fill([
                            'title' => $t['title'] ?? $entry['slug'],
                            'content' => null, // no dedicated content field in JSON; could derive later
                            'description' => $t['description'] ?? null,
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
