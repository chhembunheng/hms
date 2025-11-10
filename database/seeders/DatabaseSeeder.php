<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FaqSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(CategorySeeder::class);
        // $this->call(IntegrationSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(NavigationSeeder::class);
        $this->call(ProductSeeder::class);
    }
}
