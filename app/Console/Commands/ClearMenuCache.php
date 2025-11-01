<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearMenuCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the menu cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Cache::forget('menus_raw_data');
        $locales = config('app.available_locales', ['en']);
        foreach ($locales as $locale) {
            $pattern = "processed_menus_{$locale}_*";
            $this->info("Clearing cache pattern: {$pattern}");
        }
        Cache::flush();
        $this->info('Menu cache cleared successfully!');
        $this->info('All processed menu caches have been invalidated.');
        return Command::SUCCESS;
    }
}
