<?php

namespace Database\Seeders;

use App\Models\Frontend\Faq;
use App\Models\Frontend\FaqTranslation;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load FAQ data for both locales
        $locales = ['en', 'km'];
        
        foreach ($locales as $locale) {
            $jsonPath = public_path("site/data/{$locale}/faqs.json");
            
            if (!file_exists($jsonPath)) {
                $this->command->warn("FAQ file not found for locale: {$locale}");
                continue;
            }

            $categories = json_decode(file_get_contents($jsonPath), true);

            if (!$categories) {
                $this->command->warn("Invalid JSON in FAQ file for locale: {$locale}");
                continue;
            }

            foreach ($categories as $categoryData) {
                // Create or find the parent FAQ (category)
                $parent = Faq::firstOrCreate(
                    ['slug' => $categoryData['slug']],
                    [
                        'is_published' => true,
                        'sort' => $categoryData['id'] ?? 0,
                    ]
                );

                // Create translation for the category
                FaqTranslation::updateOrCreate(
                    [
                        'faq_id' => $parent->id,
                        'locale' => $locale,
                    ],
                    [
                        'question' => $categoryData['name'],
                        'answer' => '',
                    ]
                );

                // Create child FAQs (questions)
                if (isset($categoryData['faqs']) && is_array($categoryData['faqs'])) {
                    foreach ($categoryData['faqs'] as $faqData) {
                        // Generate a slug for the FAQ question
                        $slug = $categoryData['slug'] . '-' . $faqData['id'];
                        
                        $faq = Faq::firstOrCreate(
                            ['slug' => $slug],
                            [
                                'parent_id' => $parent->id,
                                'is_published' => true,
                                'sort' => $faqData['id'] ?? 0,
                            ]
                        );

                        // Create translation for the FAQ
                        FaqTranslation::updateOrCreate(
                            [
                                'faq_id' => $faq->id,
                                'locale' => $locale,
                            ],
                            [
                                'question' => $faqData['question'],
                                'answer' => $faqData['answer'],
                            ]
                        );
                    }
                }
            }

            $this->command->info("FAQs seeded successfully for locale: {$locale}");
        }
    }
}
