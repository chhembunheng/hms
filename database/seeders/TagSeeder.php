<?php

namespace Database\Seeders;

use App\Models\Frontend\Tag;
use App\Models\Frontend\TagTranslation;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagPath = database_path('seeders/data/frontend/tags.json');
        if (!file_exists($tagPath)) {
            throw new \Exception('Missing tags.json');
        }
        $tags = json_decode(file_get_contents($tagPath), true);
        foreach ($tags as $tag) {
            $tagModel = Tag::updateOrCreate([
                'slug' => $tag['slug'],
            ], [
                'slug' => $tag['slug'],
                'icon' => $tag['icon'] ?? null,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            foreach ($tag['translations'] as $tagTranslation) {
                TagTranslation::updateOrCreate([
                    'tag_id' => $tagModel->id,
                    'locale' => $tagTranslation['locale']
                ], [
                    'name' => $tagTranslation['name'],
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }
        }
    }
}
