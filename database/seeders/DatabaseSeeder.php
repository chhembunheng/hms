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
        $this->call(UsersTableSeeder::class);
        $this->call(MenuSeeder::class);
        // Existing ProductSeeder may seed basic products; keep it, then import site JSON to enrich
        $this->call(ProductSeeder::class);
        $this->call(ImportSiteJsonSeeder::class);
    }
}
