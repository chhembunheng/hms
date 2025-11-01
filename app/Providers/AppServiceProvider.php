<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

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
        Blueprint::macro('authors', function () {
            $this->unsignedBigInteger('created_by')->nullable()->index();
            $this->unsignedBigInteger('updated_by')->nullable()->index();
            $this->unsignedBigInteger('deleted_by')->nullable()->index();
            $this->timestamps();
            $this->softDeletes();
        });
        Blueprint::macro('fullname', function () {
            $this->string('first_name')->nullable();
            $this->string('last_name')->nullable();
            $this->string('fullname')->virtualAs("CONCAT_WS(' ', trim(first_name), trim(last_name))");
        });
        Blueprint::macro('name', function () {
            $this->string('name')->nullable();
        });
        Blueprint::macro('title', function () {
            $this->string('title')->nullable();
        });
        Blueprint::macro('label', function () {
            $this->string('label')->nullable();
        });
        Blueprint::macro('slug', function () {
            $this->string('slug')->nullable()->unique();
        });
        Blueprint::macro('description', function () {
            $this->text('description')->nullable();
        });
        Blueprint::macro('dropAuthors', function () {
            $this->dropColumn(['created_by', 'updated_by', 'deleted_by']);
            $this->dropTimestamps();
            $this->dropSoftDeletes();
        });
        Blueprint::macro('dropFullname', function () {
            $this->dropColumn(['first_name', 'last_name', 'fullname']);
        });
        Blueprint::macro('dropName', function () {
            $this->dropColumn(['name']);
        });
        Blueprint::macro('dropTitle', function () {
            $this->dropColumn(['title']);
        });
        Blueprint::macro('dropLabel', function () {
            $this->dropColumn(['label']);
        });
        Blueprint::macro('dropSlug', function () {
            $this->dropColumn(['slug']);
        });
        Blueprint::macro('dropDescription', function () {
            $this->dropColumn(['description']);
        });
    }
}
