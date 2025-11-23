<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\View;
use App\Traits\HomepageDataAdapter;
use App\Traits\SiteContentAdapter;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blueprint::macro('author', function () {
            $this->unsignedBigInteger('created_by')->nullable()->index();
            $this->unsignedBigInteger('updated_by')->nullable()->index();
            $this->unsignedBigInteger('deleted_by')->nullable()->index();
            $this->timestamps();
            $this->softDeletes();
        });
        Blueprint::macro('slug', function () {
            $this->string('slug')->nullable()->unique();
        });
        Blueprint::macro('dropAuthor', function () {
            $this->dropColumn(['created_by', 'updated_by', 'deleted_by']);
            $this->dropTimestamps();
            $this->dropSoftDeletes();
        });
        Blueprint::macro('dropSlug', function () {
            $this->dropColumn(['slug']);
        });

        // Attach dynamic homepage stats (DB-driven) to every welcome view render
        View::composer('welcome', function ($view) {
            $data = $view->getData();
            $locale = app()->getLocale();
            $adapter = new class { use HomepageDataAdapter, SiteContentAdapter; };

            // Homepage stats
            if (!array_key_exists('stats', $data)) {
                $view->with('stats', $adapter->getHomepageData());
            }

            // Common content (teams, services, products, etc.)
            $content = $adapter->getCommonContent($locale);
            foreach ($content as $key => $collection) {
                if (!array_key_exists($key, $data)) {
                    $view->with($key, $collection);
                }
            }

            // Log render event instead of relying on DB for test assertions.
            try {
                Log::info('welcome.render', [
                    'locale' => $locale,
                    'path' => request()->path(),
                    'user_count' => $view->getData()['stats']['user_count'] ?? null,
                ]);
            } catch (\Throwable $e) {
                // Silently ignore logging failures.
            }
        });
    }
}
