<?php

namespace Database\Seeders;

use App\Models\Settings\User;
use Illuminate\Database\Seeder;
use App\Models\Settings\Role;
use App\Models\Settings\RoleTranslation;
use App\Models\Settings\UserTranslation;
use App\Models\Settings\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = json_decode(file_get_contents(database_path('seeders/data/backend/users.json')), true) ?? throw new \Exception('Missing users.json');
        $rolePermissions = json_decode(file_get_contents(database_path('seeders/data/backend/role_permissions.json')), true) ?? throw new \Exception('Missing role_permissions.json');

        foreach ($roles as $role) {
            $roleModel = Role::updateOrCreate(
                [
                    'administrator' => $role['administrator'] ?? 0,
                    'sort' => $role['sort'] ?? 0,
                ], [
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
            // Create role translations for each locale
            foreach ($role['translations'] as $roleTranslation) {
                RoleTranslation::updateOrCreate(
                    [
                        'role_id' => $roleModel->id,
                        'locale' => $roleTranslation['locale'],
                    ],
                    [
                        'name' => $roleTranslation['name'] ?? null,
                        'description' => $roleTranslation['description'] ?? null,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]
                );
            }

            // Assign permissions to role
            $roleName = $role['translations'][0]['name'] ?? 'Unknown'; // Get English name
            if (isset($rolePermissions[$roleName])) {
                $this->assignPermissionsToRole($roleModel, $rolePermissions[$roleName]);
            }

            // Create users for this role
            if (isset($role['users']) && is_array($role['users'])) {
                foreach ($role['users'] as $user) {
                    $userModel = User::updateOrCreate(
                        [
                            'username' => $user['username'],
                        ],
                        [
                            'username' => $user['username'] ?? '',
                            'gender' => $user['gender'] ?? '',
                            'phone' => $user['phone'] ?? '',
                            'email' => $user['email'] ?? '',
                            'password' => bcrypt('password'),
                            'address' => $user['address'] ?? '',
                            'email_verified_at' => now(),
                            'created_by' => 1,
                            'updated_by' => 1,
                        ]
                    );

                    // Create user translations for each locale
                    foreach ($user['translations'] as $userTranslation) {
                        UserTranslation::updateOrCreate(
                            [
                                'user_id' => $userModel->id,
                                'locale' => $userTranslation['locale'],
                            ],
                            [
                                'first_name' => $userTranslation['first_name'] ?? null,
                                'last_name' => $userTranslation['last_name'] ?? null,
                                'created_by' => 1,
                                'updated_by' => 1,
                            ]
                        );
                    }
                    // Sync user roles
                    $userModel->roles()->sync([$roleModel->id]);
                }
            }
        }
    }

    /**
     * Assign permissions to a role based on the role permissions configuration.
     */
    private function assignPermissionsToRole(Role $role, array $roleConfig): void
    {
        $permissions = $roleConfig['permissions'] ?? [];

        // If role has wildcard permission (*), assign all permissions
        if (in_array('*', $permissions)) {
            $allPermissions = Permission::all();
            $role->permissions()->sync($allPermissions->pluck('id')->toArray());
            return;
        }

        // Otherwise, assign specific permissions
        $permissionIds = [];
        foreach ($permissions as $permissionPattern) {
            // Handle wildcard patterns like "dashboard.*"
            if (str_contains($permissionPattern, '*')) {
                $pattern = str_replace('*', '%', $permissionPattern);
                $matchingPermissions = Permission::where('action_route', 'like', $pattern)->get();
                $permissionIds = array_merge($permissionIds, $matchingPermissions->pluck('id')->toArray());
            } else {
                // Handle exact permission matches
                $permission = Permission::where('action_route', $permissionPattern)->first();
                if ($permission) {
                    $permissionIds[] = $permission->id;
                }
            }
        }

        $role->permissions()->sync(array_unique($permissionIds));
    }
}
