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
        $categories = [
            [
                'slug' => 'technology',
                'icon' => 'fas fa-laptop',
                'sort' => 1,
                'translations' => [
                    'en' => [
                        'name' => 'Technology',
                        'description' => 'Latest technology news and updates',
                        'content' => 'Technology category content',
                    ],
                    'km' => [
                        'name' => 'បច្ចេកវិទ្យា',
                        'description' => 'ព័ត៌មាន និងការធ្វើបច្ចុប្បន្នភាពថ្មីៗនៃបច្ចេកវិទ្យា',
                        'content' => 'មាតិកាប្រភេទបច្ចេកវិទ្យា',
                    ],
                ],
            ],
            [
                'slug' => 'business',
                'icon' => 'fas fa-briefcase',
                'sort' => 2,
                'translations' => [
                    'en' => [
                        'name' => 'Business',
                        'description' => 'Business insights and strategies',
                        'content' => 'Business category content',
                    ],
                    'km' => [
                        'name' => 'អាជីវកម្ម',
                        'description' => 'ការយល់ដឹង និងយុទ្ធសាស្រ្តអាជីវកម្ម',
                        'content' => 'មាតិកាប្រភេទអាជីវកម្ម',
                    ],
                ],
            ],
            [
                'slug' => 'education',
                'icon' => 'fas fa-graduation-cap',
                'sort' => 3,
                'translations' => [
                    'en' => [
                        'name' => 'Education',
                        'description' => 'Educational resources and learning',
                        'content' => 'Education category content',
                    ],
                    'km' => [
                        'name' => 'ការអប់រំ',
                        'description' => 'ធនធានអប់រំ និងការរៀនសូត្រ',
                        'content' => 'មាតិកាប្រភេទការអប់រំ',
                    ],
                ],
            ],
        ];

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
