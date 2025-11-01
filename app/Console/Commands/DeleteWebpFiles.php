<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteWebpFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage:
     *   php artisan webp:clear
     *   php artisan webp:clear --force
     */
    protected $signature = 'webp:clear
                            {--force : Delete without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Recursively delete all .webp files from the Laravel project root (base_path())';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Always operate on the Laravel project root
        $basePath = base_path();
        // Find all files under base_path, then filter only .webp
        $files = collect(File::allFiles($basePath))
            ->filter(function ($file) {
                return strtolower($file->getExtension()) === 'webp';
            })
            ->values();
        $count = $files->count();
        if ($count === 0) {
            $this->info("No .webp files found under {$basePath}");
            return Command::SUCCESS;
        }
        // Show a preview of what will be deleted
        $this->info("Found {$count} .webp file(s) under {$basePath}:");
        foreach ($files as $file) {
            $this->line(' - ' . $file->getPathname());
        }
        // Ask before deleting unless --force is used
        if (! $this->option('force')) {
            if (! $this->confirm("DELETE ALL {$count} file(s)? This cannot be undone.", false)) {
                $this->warn('Aborted. No files deleted.');
                return Command::SUCCESS;
            }
        }
        // Delete the files
        $deleted = 0;
        foreach ($files as $file) {
            try {
                File::delete($file->getPathname());
                $deleted++;
            } catch (\Throwable $e) {
                $this->error("Failed to delete: " . $file->getPathname());
            }
        }
        $this->info("Done. Deleted {$deleted} / {$count} .webp file(s).");
        return Command::SUCCESS;
    }
}