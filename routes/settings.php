<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Settings\MenuController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\Settings\MyAccountController;
use App\Http\Controllers\Settings\ExchangeRateController;

Route::get('clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Cache::flush();
    return redirect()->back();
})->name('clear-cache');

Route::get('/lang/{lang}', [\App\Http\Controllers\LanguageController::class, 'setLanguage'])->name('admin.lang');

    Route::get('/', function () {
        return redirect()->route('dashboard.index');
    });
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index')->middleware(['abilities', 'auth', 'verified']);

    Route::group(['prefix' => 'settings', 'as' => 'settings.', 'middleware' => ['auth', 'verified', 'abilities']], function () {
        Route::prefix('menus')->name('menus.')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [MenuController::class, 'add'])->name('add');
            Route::match(['get', 'post'], '/{id}/edit', [MenuController::class, 'edit'])->name('edit');
            Route::delete('/{id}/delete', [MenuController::class, 'destroy'])->name('delete');
            Route::get('/select2', [MenuController::class, 'select2'])->name('select2');
        });
        Route::prefix('exchange-rate')->name('exchange-rate.')->group(function () {
            Route::get('/', [ExchangeRateController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [ExchangeRateController::class, 'add'])->name('add');
            Route::match(['get', 'post'], '/{id}/edit', [ExchangeRateController::class, 'edit'])->name('edit');
            Route::delete('/{id}/delete', [ExchangeRateController::class, 'delete'])->name('delete');
        });
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [UserController::class, 'add'])->name('add');
            Route::match(['get', 'post'], '/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::delete('/{id}/delete', [UserController::class, 'destroy'])->name('delete');
        });
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [RoleController::class, 'add'])->name('add');
            Route::match(['get', 'post'], '/{id}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::delete('/{id}/delete', [RoleController::class, 'destroy'])->name('delete');
            Route::get('/select2', [RoleController::class, 'select2'])->name('select2');
        });
        Route::prefix('security')->name('security.')->group(function () {

            Route::match(['get', 'post'], '/authenticator', [MyAccountController::class, 'authenticator'])->name('authenticator');
            Route::match(['get', 'post'], '/change-password', [MyAccountController::class, 'changePassword'])->name('change-password');
        });
        Route::prefix('my-account')->name('my-account.')->group(function () {
            Route::get('/', [MyAccountController::class, 'index'])->name('index');
            Route::get('/enable-2fa', [MyAccountController::class, 'enableTwoFactorAuthentication'])->name('enable-2fa');
            Route::get('/events', [MyAccountController::class, 'events'])->name('events');
        });
    });
