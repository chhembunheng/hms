<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\Blog;
use App\Models\Frontend\BlogTranslation;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/frontend/blogs.json');

        if (! File::exists($path)) {
            $this->command?->warn("BlogSeeder: blogs.json not found at $path (skipping)");
            return;
        }

        $data = json_decode(File::get($path), true);
        if (! is_array($data)) {
            $this->command?->error('BlogSeeder: Invalid JSON structure.');
            return;
        }

        DB::transaction(function () use ($data) {
            foreach ($data as $index => $entry) {
                if (! isset($entry['slug'])) {
                    $this->command?->warn("BlogSeeder: Skipping entry at index {$index} (missing slug)");
                    continue;
                }

                $blog = Blog::query()->firstOrNew(['slug' => $entry['slug']]);
                $blog->fill([
                    'author_id' => $blog->author_id ?? 1,
                    'image' => $entry['image'] ?? $entry['image'] ?? null,
                    'is_published' => $entry['is_published'] ?? true,
                    'sort' => $entry['sort'] ?? ($index + 1),
                ]);
                $blog->save();

                $translations = $entry['translations'] ?? [];
                if (is_array($translations)) {
                    foreach ($translations as $tIdx => $t) {
                        $locale = $t['locale'] ?? 'en';
                        $title = $t['title'] ?? $entry['slug'];
                        $excerpt = $t['excerpt'] ?? ($t['short_description'] ?? null);
                        $body = $t['body'] ?? ($t['content'] ?? null);

                        $translation = BlogTranslation::query()->firstOrNew([
                            'blog_id' => $blog->id,
                            'locale' => $locale,
                        ]);
                        $translation->fill([
                            'title' => $title,
                            'excerpt' => $excerpt,
                            'body' => $body,
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
