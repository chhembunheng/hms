<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\Integration;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Models\Frontend\IntegrationTranslation;

class IntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/frontend/integrations.json');
        if (! File::exists($path)) {
            $this->command?->warn("IntegrationSeeder: integrations.json not found at $path");
            return;
        }

        $data = json_decode(File::get($path), true);
        if (! is_array($data)) {
            $this->command?->error('IntegrationSeeder: Invalid JSON structure.');
            return;
        }
        Schema::disableForeignKeyConstraints();

        IntegrationTranslation::truncate();
        Integration::truncate();

        Schema::enableForeignKeyConstraints();


        DB::transaction(function () use ($data) {
            $i = 0;
            foreach ($data as $item) {
                $slug = $item['slug'];
                $model = Integration::query()->firstOrNew(['slug' => $slug]);
                $model->fill([
                    'image' => $item['image'] ?? null,
                    'images' => $item['images'] ?? null,
                    'icon'  => $item['icon'] ?? null,
                    'sort'  => ++$i,
                    'parent_id'  => null,
                    'is_active'  => true,
                    'created_by' => $model->exists ? $model->created_by : 1,
                    'updated_by' => 1,
                ]);
                $model->save();
                $translations = $item['transactions'] ?? $item['translations'] ?? [];
                if (is_array($translations)) {
                    foreach ($translations as $t) {
                        $locale = $t['locale'] ?? 'en';
                        $translation = IntegrationTranslation::query()->firstOrNew([
                            'integration_id' => $model->id,
                            'locale' => $locale,
                        ]);
                        $translation->fill([
                            'name' => $t['name'] ?? null,
                            'description' => $t['description'] ?? null,
                            'content' => $t['content'] ?? null,
                            'created_by'  => $translation->exists ? $translation->created_by : 1,
                            'updated_by'  => 1,
                        ]);
                        $translation->save();
                    }
                }
                $integrations = $item['integrations'] ?? [];
                if (!empty($integrations) && is_array($integrations)) {
                    foreach ($integrations as $integration) {
                        $slug = $item['slug'] . '-' . $integration['slug'];
                        $integrationModel = Integration::query()->firstOrNew(['slug' => $slug]);
                        $integrationModel->fill([
                            'image' => $integration['image'] ?? null,
                            'icon' => $integration['icon'] ?? null,
                            'images' => $integration['images'] ?? null,
                            'sort' => $integration['id'] ?? 0,
                            'slug' => $slug,
                            'parent_id'  => $model->id,
                            'is_active'  => true,
                            'created_by' => $integrationModel->exists ? $integrationModel->created_by : 1,
                            'updated_by' => 1,
                        ]);
                        $integrationModel->save();
                        $integrationTranslations = $integration['transactions'] ?? [];
                        foreach ($integrationTranslations as $ft) {
                            $locale = $ft['locale'] ?? 'en';
                            $integrationModelTrans = IntegrationTranslation::query()->firstOrNew([
                                'integration_id' => $integrationModel->id,
                                'locale' => $locale,
                            ]);
                            $integrationModelTrans->fill([
                                'name' => $ft['name'] ?? null,
                                'description' => $ft['description'] ?? null,
                                'content' => $ft['content'] ?? null,
                                'created_by' => $integrationModelTrans->exists ? $integrationModelTrans->created_by : 1,
                                'updated_by' => 1,
                            ]);

                            $integrationModelTrans->save();
                        }
                        $features = $integration['features'] ?? [];
                        foreach ($features as $feature) {
                            $slug = $item['slug'] . '-' . $integration['slug'] . '-' . $feature['slug'];
                            $featureModel = Integration::query()->firstOrNew(['slug' => $slug]);
                            $featureModel->fill([
                                'image' => $feature['image'] ?? null,
                                'icon' => $feature['icon'] ?? null,
                                'images' => $feature['images'] ?? null,
                                'sort' => $feature['id'] ?? 0,
                                'slug' => $slug,
                                'parent_id'  => $integrationModel->id,
                                'is_active'  => true,
                                'created_by' => $featureModel->exists ? $featureModel->created_by : 1,
                                'updated_by' => 1,
                            ]);
                            $featureModel->save();
                            $featureTranslations = $feature['transactions'] ?? [];
                            foreach ($featureTranslations as $ft) {
                                $locale = $ft['locale'] ?? 'en';
                                $featureModelTrans = IntegrationTranslation::query()->firstOrNew([
                                    'integration_id' => $featureModel->id,
                                    'locale' => $locale,
                                ]);
                                $featureModelTrans->fill([
                                    'name' => $ft['name'] ?? null,
                                    'description' => $ft['description'] ?? null,
                                    'content' => $ft['content'] ?? null,
                                    'created_by' => $featureModelTrans->exists ? $featureModelTrans->created_by : 1,
                                    'updated_by' => 1,
                                ]);
                                $featureModelTrans->save();
                            }
                        }
                    }
                } // end item
            } // end category
        });
    }
}
