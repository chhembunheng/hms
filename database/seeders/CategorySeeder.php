<?php

namespace Database\Seeders;

use App\Models\Frontend\Category;
use App\Models\Frontend\CategoryTranslation;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $jsonPath = database_path('seeders/data/frontend/categories.json');
        if (!file_exists($jsonPath)) {
            $this->command->error("JSON file for categories not found at path: {$jsonPath}");
            return;
        }
        $jsonData = file_get_contents($jsonPath);
        $categories = json_decode($jsonData, true);

        foreach ($categories as $categoryData) {
            $category = Category::updateOrCreate([
                'slug' => $categoryData['slug'],
            ], [
                'slug' => $categoryData['slug'],
                'icon' => $categoryData['icon'],
                'sort' => $categoryData['sort'],
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            foreach ($categoryData['translations'] as $locale => $translation) {
                CategoryTranslation::updateOrCreate([
                    'category_id' => $category->id,
                    'locale' => $locale,
                ], [
                    'name' => $translation['name'],
                    'description' => $translation['description'],
                    'content' => $translation['content'],
                ]);
            }
        }
    }
}
