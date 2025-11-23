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
    }
}
