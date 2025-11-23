<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Frontend\Team;
use App\Models\Frontend\Choosing;
use App\Models\Frontend\Plan;

class PopulateMissingDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTeams();
        $this->seedChoosing();
        $this->seedPlans();
    }

    private function seedTeams(): void
    {
        $teams = [
            ['slug' => 'john-doe', 'photo' => 'team-ceo.jpg', 'is_active' => 1],
            ['slug' => 'jane-smith', 'photo' => 'team-cto.jpg', 'is_active' => 1],
            ['slug' => 'bob-johnson', 'photo' => 'team-lead-dev.jpg', 'is_active' => 1],
            ['slug' => 'alice-wilson', 'photo' => 'team-designer.jpg', 'is_active' => 1],
        ];

        foreach ($teams as $idx => $team) {
            $t = Team::firstOrCreate(['slug' => $team['slug']], [
                'photo' => $team['photo'],
                'is_active' => $team['is_active'],
            ]);

            // Add translations for both locales
            $t->translations()->updateOrCreate(
                ['locale' => 'en'],
                ['name' => ucwords(str_replace('-', ' ', $team['slug'])), 'position_name' => 'Position ' . ($idx + 1)]
            );
            $t->translations()->updateOrCreate(
                ['locale' => 'km'],
                ['name' => "សមាជិកក្រុម " . ($idx + 1), 'position_name' => "មុខងារ " . ($idx + 1)]
            );
        }
    }

    private function seedChoosing(): void
    {
        $choosingItems = [
            ['image' => 'why-1.jpg', 'sort' => 1],
            ['image' => 'why-2.jpg', 'sort' => 2],
            ['image' => 'why-3.jpg', 'sort' => 3],
            ['image' => 'why-4.jpg', 'sort' => 4],
        ];

        foreach ($choosingItems as $idx => $item) {
            $c = Choosing::firstOrCreate(['image' => $item['image']], [
                'sort' => $item['sort'],
                'is_active' => 1,
            ]);

            // Add translations
            $c->translations()->updateOrCreate(
                ['locale' => 'en'],
                ['title' => "Why Choose Us - " . ($idx + 1), 'description' => "Reason " . ($idx + 1) . " description in English"]
            );
            $c->translations()->updateOrCreate(
                ['locale' => 'km'],
                ['title' => "ហេតុផលជ្រើសរើស - " . ($idx + 1), 'description' => "ការពិពណ៌នា " . ($idx + 1) . " ជាភាសាខ្មែរ"]
            );
        }
    }

    private function seedPlans(): void
    {
        $plans = [
            ['sort' => 1, 'is_active' => 1],
            ['sort' => 2, 'is_active' => 1],
            ['sort' => 3, 'is_active' => 1],
        ];

        foreach ($plans as $idx => $plan) {
            $p = Plan::firstOrCreate(['sort' => $plan['sort']], [
                'is_active' => $plan['is_active'],
            ]);

            // Add translations
            $p->translations()->updateOrCreate(
                ['locale' => 'en'],
                ['name' => "Plan " . ($idx + 1), 'description' => "Plan " . ($idx + 1) . " features"]
            );
            $p->translations()->updateOrCreate(
                ['locale' => 'km'],
                ['name' => "ផែនការ " . ($idx + 1), 'description' => "លក្ខណៈពិសេស ផែនការ " . ($idx + 1)]
            );
        }
    }
}
