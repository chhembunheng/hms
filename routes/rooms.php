<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Rooms\RoomController;
use App\Http\Controllers\Rooms\RoomTypeController;
use App\Http\Controllers\Rooms\RoomStatusController;
use App\Http\Controllers\Rooms\RoomPricingController;

Route::group(['prefix' => 'rooms', 'as' => 'rooms.', 'middleware' => ['auth', 'verified', 'abilities']], function () {

    // Room List
    Route::prefix('list')->name('list.')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [RoomController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [RoomController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [RoomController::class, 'delete'])->name('delete');
    });

    // Room Types
    Route::prefix('type')->name('type.')->group(function () {
        Route::get('/', [RoomTypeController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [RoomTypeController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [RoomTypeController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [RoomTypeController::class, 'delete'])->name('delete');
    });

    // Room Statuses
    Route::prefix('status')->name('status.')->group(function () {
        Route::get('/', [RoomStatusController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [RoomStatusController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [RoomStatusController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [RoomStatusController::class, 'delete'])->name('delete');
    });

    // Room Pricing
    Route::prefix('pricing')->name('pricing.')->group(function () {
        Route::get('/', [RoomPricingController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [RoomPricingController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [RoomPricingController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [RoomPricingController::class, 'delete'])->name('delete');
    });
});
