<?php

namespace App\Traits;

use App\Models\User;

trait HomepageDataAdapter
{
    /**
     * Aggregate dynamic homepage data sourced from the database.
     */
    public function getHomepageData(): array
    {
        try {
            $count = User::count();
            $latest = User::latest()->take(5)->get(['id', 'first_name', 'last_name']);
        } catch (\Throwable $e) {
            // DB driver missing or connection error: degrade gracefully.
            $count = 0;
            $latest = collect();
        }
        return [
            'user_count' => $count,
            'latest_users' => $latest,
        ];
    }
}
