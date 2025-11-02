<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\Menu;
use App\Models\Settings\MenuTranslation;
use Illuminate\Support\Facades\File;
use App\Models\Settings\Permission;
use App\Models\Settings\PermissionTranslation;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = json_decode(File::get(database_path('seeders/data/menus.json')), true) ?? throw new \Exception('Missing menus.json');
        $locales = config('init.available_locales', ['en']);
        $this->createMenus($menus, null, $locales);
    }

    private function createMenus($menus, $parentId = null, $locales = []): void
    {
        foreach ($menus as $data) {
            $menu = Menu::updateOrCreate(
                [
                    'parent_id' => $parentId,
                    'icon' => $data['icon'],
                    'route' => $data['route'] ?? null,
                    'order' => $data['order'],
                ],
                [
                    'parent_id' => $parentId,
                    'icon' => $data['icon'],
                    'route' => $data['route'] ?? null,
                    'order' => $data['order'],
                ]
            );
            foreach ($locales as $locale) {
                $nameKey = 'name_' . $locale;
                $descriptionKey = 'description_' . $locale;
                $name = $data[$nameKey] ?? $data['name'] ?? 'Untitled';
                $description = $data[$descriptionKey] ?? $data['description'] ?? null;

                MenuTranslation::updateOrCreate(
                    [
                        'menu_id' => $menu->id,
                        'locale' => $locale,
                    ],
                    [
                        'name' => $name,
                        'description' => $description,
                        'created_by' => 1,
                    ]
                );
            }
            if (!empty($data['permissions'])) {
                foreach ($data['permissions'] as $permissionData) {
                    $permission = Permission::updateOrCreate(
                        [
                            'action_route' => $this->actionRoute($data['route'], $permissionData['action']),
                            'menu_id' => $menu->id,
                        ],
                        [
                            'menu_id' => $menu->id,
                            'action' => $permissionData['action'],
                            'icon' => $permissionData['icon'],
                            'target' => $permissionData['target'],
                            'order' => $permissionData['order'],
                        ]
                    );

                    // Create permission translations for each locale
                    foreach ($locales as $locale) {
                        $permNameKey = 'name_' . $locale;
                        $permDescKey = 'description_' . $locale;
                        $permName = $permissionData[$permNameKey] ?? $permissionData['name'] ?? 'Action';
                        $permDesc = $permissionData[$permDescKey] ?? $permissionData['description'] ?? null;

                        PermissionTranslation::updateOrCreate(
                            [
                                'permission_id' => $permission->id,
                                'locale' => $locale,
                            ],
                            [
                                'name' => $permName,
                                'description' => $permDesc,
                                'created_by' => 1,
                            ]
                        );
                    }
                }
            }

            // Recursively create child menus
            if (!empty($data['children'])) {
                $this->createMenus($data['children'], $menu->id, $locales);
            }
        }
    }

    private function actionRoute($route, $action): ?string
    {
        return str_replace('.index', '.' . $action, $route);
    }
}
