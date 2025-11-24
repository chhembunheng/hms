<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Frontend\Team;
use App\Models\Frontend\TeamTranslation;
use Illuminate\Support\Facades\File;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/data/frontend/teams.json'));
        $teams = json_decode($json, true);

        foreach ($teams as $teamData) {
            $team = Team::updateOrCreate(
                ['slug' => $teamData['slug']],
                [
                    'photo' => $teamData['photo'] ?? null,
                    'is_active' => $teamData['is_active'] ?? true,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );

            // Create translations
            if (isset($teamData['translations']) && is_array($teamData['translations'])) {
                foreach ($teamData['translations'] as $translation) {
                    TeamTranslation::updateOrCreate(
                        [
                            'team_id' => $team->id,
                            'locale' => $translation['locale'],
                        ],
                        [
                            'name' => $translation['name'] ?? null,
                            'position_name' => $translation['position'] ?? null,
                            'bio' => $translation['bio'] ?? null,
                            'description' => $translation['description'] ?? null,
                            'content' => $translation['content'] ?? null,
                            'created_by' => 1,
                            'updated_by' => 1,
                        ]
                    );
                }
            }
        }
    }
}

