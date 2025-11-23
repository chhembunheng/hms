<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\Choosing;
use App\Models\Frontend\ChoosingTranslation;

class ChoosingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/frontend/choosings.json');

        if (! File::exists($path)) {
            $this->command?->warn("ChoosingSeeder: choosings.json not found at $path (skipping)");
            return;
        }

        $data = json_decode(File::get($path), true);
        if (! is_array($data)) {
            $this->command?->error('ChoosingSeeder: Invalid JSON structure.');
            return;
        }

        DB::transaction(function () use ($data) {
            foreach ($data as $index => $entry) {
                // Expect: image, sort, is_active, translations[] { locale, title, description }
                $translations = $entry['translations'] ?? [];

                $choosing = Choosing::query()->create([
                    'image' => $entry['image'] ?? null,
                    'sort' => $entry['sort'] ?? ($index + 1),
                    'is_active' => $entry['is_active'] ?? true,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);

                if (is_array($translations)) {
                    foreach ($translations as $t) {
                        $locale = $t['locale'] ?? 'en';
                        ChoosingTranslation::query()->create([
                            'choosing_id' => $choosing->id,
                            'locale' => $locale,
                            'title' => $t['title'] ?? 'N/A',
                            'description' => $t['description'] ?? null,
                            'created_by' => 1,
                            'updated_by' => 1,
                        ]);
                    }
                }
            }
        });
    }
}
