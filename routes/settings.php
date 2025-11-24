<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Settings\MyAccountController;
use App\Http\Controllers\Settings\MenuController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\Settings\PermissionController;

Route::get('clear-cache', function () {
    Artisan::call('optimize:clear');
    return redirect()->back();
})->name('clear-cache');
Route::domain(config('app.admin_domain'))->group(function () {
    Route::group(['prefix' => 'settings', 'as' => 'settings.', 'middleware' => ['auth', 'verified', 'abilities']], function () {
        Route::prefix('menus')->name('menus.')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [MenuController::class, 'add'])->name('add');
            Route::match(['get', 'post'], '/{id}/edit', [MenuController::class, 'edit'])->name('edit');
            Route::delete('/{id}/delete', [MenuController::class, 'destroy'])->name('delete');
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
        });
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [PermissionController::class, 'add'])->name('add');
            Route::match(['get', 'post'], '/{id}/edit', [PermissionController::class, 'edit'])->name('edit');
            Route::delete('/{id}/delete', [PermissionController::class, 'destroy'])->name('delete');
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
});
