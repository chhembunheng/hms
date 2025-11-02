<?php

namespace Database\Seeders;

use App\Models\Settings\User;
use Illuminate\Database\Seeder;
use App\Models\Settings\Role;
use App\Models\Settings\RoleTranslation;
use App\Models\Settings\UserTranslation;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleData = json_decode(file_get_contents(database_path('seeders/data/users.json')), true) ?? throw new \Exception('Missing users.json');
        $locales = config('init.available_locales', ['en']);

        foreach ($roleData as $roleInfo) {
            // Create role without name (structural data)
            $roleModel = Role::updateOrCreate(
                [
                    'administrator' => $roleInfo['administrator'] ?? 0,
                    'order' => $roleInfo['order'] ?? 0,
                ],
                [
                    'administrator' => $roleInfo['administrator'] ?? 0,
                    'order' => $roleInfo['order'] ?? 0,
                ]
            );

            // Create role translations for each locale
            foreach ($locales as $locale) {
                $nameKey = 'name_' . $locale;
                $descKey = 'description_' . $locale;
                $name = $roleInfo[$nameKey] ?? $roleInfo['name'] ?? 'Role';
                $description = $roleInfo[$descKey] ?? null;

                RoleTranslation::updateOrCreate(
                    [
                        'role_id' => $roleModel->id,
                        'locale' => $locale,
                    ],
                    [
                        'name' => $name,
                        'description' => $description,
                        'created_by' => 1,
                    ]
                );
            }

            // Create users for this role
            if (isset($roleInfo['users']) && is_array($roleInfo['users'])) {
                foreach ($roleInfo['users'] as $userData) {
                    $userModel = User::updateOrCreate(
                        ['username' => $userData['username']],
                        [
                            'username' => $userData['username'] ?? '',
                            'gender' => $userData['gender'] ?? '',
                            'phone' => $userData['phone'] ?? '',
                            'email' => $userData['email'] ?? '',
                            'password' => bcrypt('password'),
                            'address' => $userData['address'] ?? '',
                            'email_verified_at' => now(),
                        ]
                    );

                    // Create user translations for each locale
                    foreach ($locales as $locale) {
                        $firstNameKey = 'first_name_' . $locale;
                        $lastNameKey = 'last_name_' . $locale;
                        $firstName = $userData[$firstNameKey] ?? $userData['first_name'] ?? '';
                        $lastName = $userData[$lastNameKey] ?? $userData['last_name'] ?? '';

                        UserTranslation::updateOrCreate(
                            [
                                'user_id' => $userModel->id,
                                'locale' => $locale,
                            ],
                            [
                                'first_name' => $firstName,
                                'last_name' => $lastName,
                                'created_by' => 1,
                            ]
                        );
                    }

                    // Sync user roles
                    $userModel->roles()->sync([$roleModel->id]);
                }
            }
        }
    }
}
