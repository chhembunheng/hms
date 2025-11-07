<?php

namespace Database\Seeders;

use App\Models\Frontend\Product;
use App\Models\Frontend\ProductTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load product data from English locale first
        $filePath = public_path("site/data/en/products.json");

        if (!file_exists($filePath)) {
            $this->command->warn("Product file not found: {$filePath}");
            return;
        }

        $productsData = json_decode(file_get_contents($filePath), true);
        $productMap = []; // Map old IDs to new product instances

        foreach ($productsData as $productData) {
            // Generate SKU if not present in JSON
            $sku = $productData['sku'] ?? 'PROD-' . str_pad($productData['id'], 3, '0', STR_PAD_LEFT);
            
            // Generate slug from product name or slug field
            $slug = !empty($productData['slug'])
                ? Str::slug($productData['slug'])
                : Str::slug($productData['name'] ?? 'product-' . $productData['id']);

            // Create or find product by SKU
            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'slug' => $slug,
                    'image' => $productData['thumb'] ?? $productData['cover'] ?? null,
                    'sort' => isset($productData['sort']) ? (int)$productData['sort'] : 0,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );

            // Store mapping of old ID to new product
            $productMap[$productData['id']] = $product->id;
        }

        // Now add translations for all locales
        $locales = config('init.available_locales', ['en', 'km']);

        foreach ($locales as $locale) {
            $filePath = public_path("site/data/{$locale}/products.json");

            if (!file_exists($filePath)) {
                $this->command->warn("Product file not found: {$filePath}");
                continue;
            }

            $productsData = json_decode(file_get_contents($filePath), true);

            foreach ($productsData as $index => $productData) {
                // Use mapped product ID
                $newProductId = $productMap[$productData['id']] ?? null;

                if (!$newProductId) {
                    continue;
                }

                // Create translation
                ProductTranslation::updateOrCreate(
                    [
                        'product_id' => $newProductId,
                        'locale' => $locale,
                    ],
                    [
                        'name' => $productData['name'] ?? 'Unnamed Product',
                        'short_description' => $productData['short_description'] ?? null,
                        'description' => $productData['description'] ?? null,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]
                );
            }
        }

        $this->command->info('Products seeded successfully!');
    }
}
