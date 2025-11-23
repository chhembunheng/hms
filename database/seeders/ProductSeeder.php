<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Frontend\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\ProductFeature;
use App\Models\Frontend\ProductTranslation;
use App\Models\Frontend\ProductFeatureTranslation;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/frontend/products.json');

        if (!file_exists($filePath)) {
            $this->command->warn("Product file not found: {$filePath}");
            return;
        }

        $products = json_decode(file_get_contents($filePath), true);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProductTranslation::truncate();
        Product::truncate();
        ProductFeatureTranslation::truncate();
        ProductFeature::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->warn("🧹 Existing Product data truncated.");

        foreach ($products as $product) {
            $productModel = Product::updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'slug' => $product['slug'],
                    'image' => $product['thumb'] ?? $product['cover'] ?? null,
                    'sort' => isset($product['sort']) ? (int)$product['sort'] : 0,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
            if (isset($product['translations']) && is_array($product['translations'])) {
                foreach ($product['translations'] as $productTranslation) {
                    ProductTranslation::updateOrCreate(
                        [
                            'product_id' => $productModel->id,
                            'locale' => $productTranslation['locale'] ?? 'en',
                        ],
                        [
                            'name' => $productTranslation['name'] ?? '',
                            'description' => $productTranslation['description'] ?? '',
                            'content' => $productTranslation['content'] ?? '',
                            'created_by' => 1,
                            'updated_by' => 1,
                        ]
                    );
                }
            }
            if (isset($product['features']) && is_array($product['features'])) {
                foreach ($product['features'] as $feature) {
                    $productFeatureModel = ProductFeature::create([
                        'product_id' => $productModel->id,
                        'icon' => $feature['icon'] ?? null,
                        'sort' => isset($feature['sort']) ? (int)$feature['sort'] : 0,
                        'is_highlighted' => 1,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]);
                    if (isset($feature['translations']) && is_array($feature['translations'])) {
                        foreach ($feature['translations'] as $featureTranslation) {
                            $productFeatureModel->translations()->create([
                                'locale' => $featureTranslation['locale'] ?? 'en',
                                'title' => $featureTranslation['title'] ?? '',
                                'description' => $featureTranslation['description'] ?? '',
                                'created_by' => 1,
                                'updated_by' => 1,
                            ]);
                        }
                    }
                    if (isset($feature['details']) && is_array($feature['details'])) {
                        foreach ($feature['details'] as $detail) {
                            $featureDetailModel = $productFeatureModel->details()->create([
                                'icon' => $detail['icon'] ?? null,
                                'sort' => isset($detail['sort']) ? (int)$detail['sort'] : 0,
                                'created_by' => 1,
                                'updated_by' => 1,
                            ]);
                            if (isset($detail['translations']) && is_array($detail['translations'])) {
                                foreach ($detail['translations'] as $detailTranslation) {
                                    $featureDetailModel->translations()->create([
                                        'locale' => $detailTranslation['locale'] ?? 'en',
                                        'title' => $detailTranslation['title'] ?? '',
                                        'description' => $detailTranslation['description'] ?? '',
                                        'created_by' => 1,
                                        'updated_by' => 1,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->command->info('Products seeded successfully!');
    }
}
