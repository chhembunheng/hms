<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckIn\WalkInController;
use App\Http\Controllers\CheckIn\StayingController;
use App\Http\Controllers\CheckIn\VoidStayController;
use App\Http\Controllers\CheckoutController;

Route::group(['prefix' => 'checkin', 'as' => 'checkin.', 'middleware' => ['auth', 'verified', 'abilities']], function () {

    // Walk-In Check-In
    Route::prefix('walkin')->name('walkin.')->group(function () {
        Route::get('/', [WalkInController::class, 'index'])->name('index');
        Route::get('/available-rooms', [WalkInController::class, 'getAvailableRooms'])->name('available-rooms');
        Route::match(['get', 'post'], '/add', [WalkInController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [WalkInController::class, 'edit'])->name('edit');
        Route::post('/{id}/cancel', [WalkInController::class, 'cancel'])->name('cancel');
        Route::delete('/{id}/delete', [WalkInController::class, 'delete'])->name('delete');
    });

    // Staying Guests
    Route::prefix('staying')->name('staying.')->group(function () {
        Route::get('/', [StayingController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/{id}/edit', [StayingController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [StayingController::class, 'delete'])->name('delete');
        Route::post('/{id}/check-out', [StayingController::class, 'checkOut'])->name('check-out');
    });


    // Void/Cancelled Stays
    Route::prefix('void-stay')->name('void-stay.')->group(function () {
        Route::get('/', [VoidStayController::class, 'index'])->name('index');
    });
});

// Check-Out & Payment
Route::group(['prefix' => 'checkout', 'as' => 'checkout.', 'middleware' => ['auth', 'verified', 'abilities']], function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::match(['get', 'post'], '/{id}/edit', [CheckoutController::class, 'edit'])->name('edit');
    Route::delete('/{id}/delete', [CheckoutController::class, 'delete'])->name('delete');
});
