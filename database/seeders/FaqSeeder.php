<?php

namespace Database\Seeders;

use App\Models\Frontend\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\FaqTranslation;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path("seeders/data/frontend/faqs.json");

        if (!file_exists($jsonPath)) {
            $this->command->warn("FAQ JSON file not found at: {$jsonPath}");
            return;
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FaqTranslation::truncate();
        Faq::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->warn("🧹 Existing FAQ data truncated.");


        $categories = json_decode(file_get_contents($jsonPath), true);

        if (!$categories) {
            $this->command->warn("Invalid or empty FAQ JSON file.");
            return;
        }

        foreach ($categories as $categoryData) {
            // --- Create or update main category ---
            $parent = Faq::updateOrCreate(
                ['slug' => $categoryData['slug']],
                [
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );

            // --- Category translations ---
            if (!empty($categoryData['translations'])) {
                foreach ($categoryData['translations'] as $translation) {
                    FaqTranslation::updateOrCreate(
                        [
                            'faq_id' => $parent->id,
                            'locale' => $translation['locale'],
                        ],
                        [
                            'question' => $translation['question'] ?? null,
                            'answer' => $translation['answer'] ?? null,
                            'created_by' => 1,
                            'updated_by' => 1,
                        ]
                    );
                }
            }

            // --- Child FAQs ---
            if (!empty($categoryData['faqs'])) {
                foreach ($categoryData['faqs'] as $faqData) {
                    $slug = $faqData['slug'] ?? slug($faqData['question']);
                    $faq = Faq::updateOrCreate(
                        ['slug' => $slug],
                        [
                            'parent_id' => $parent->id,
                            'slug' => $slug,
                            'created_by' => 1,
                            'updated_by' => 1,
                        ]
                    );

                    // --- FAQ translations ---
                    if (!empty($faqData['translations'])) {
                        foreach ($faqData['translations'] as $translation) {
                            FaqTranslation::updateOrCreate(
                                [
                                    'faq_id' => $faq->id,
                                    'locale' => $translation['locale'],
                                ],
                                [
                                    'question' => $translation['question'] ?? null,
                                    'answer' => $translation['answer'] ?? null,
                                    'created_by' => 1,
                                    'updated_by' => 1,
                                ]
                            );
                        }
                    }
                }
            }
        }

        $this->command->info("✅ FAQs seeded successfully!");
    }
}
