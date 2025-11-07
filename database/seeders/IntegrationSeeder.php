<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Frontend\Integration;
use Illuminate\Support\Facades\File;

class IntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $integrations = json_decode(File::get(database_path('seeders/data/frontend/integrations.json')), true) ?? throw new \Exception('Missing integrations.json');

        if (empty($integrations)) {
            return;
        }
        // Process integrations here
        foreach ($integrations as $integration) {
            dd($integration);die;
            $integrationModel = Integration::updateOrCreate([
                'name' => $integration['name'],
            ], [
                'created_by' => 1,
                'updated_by' => 1,
            ]);

        }
    }
}
