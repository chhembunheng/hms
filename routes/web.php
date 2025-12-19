<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckInController;

Route::middleware(['auth', 'verified', 'abilities'])->group(function () {
    // Check-ins
    Route::prefix('check-ins')->name('check-ins.')->group(function () {
        Route::get('/', [CheckInController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/create', [CheckInController::class, 'create'])->name('create');
        Route::get('/{id}', [CheckInController::class, 'show'])->name('show');
        Route::match(['get', 'post'], '/{id}/edit', [CheckInController::class, 'edit'])->name('edit');
        Route::delete('/{id}', [CheckInController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/check-in', [CheckInController::class, 'checkIn'])->name('check-in');
        Route::post('/{id}/check-out', [CheckInController::class, 'checkOut'])->name('check-out');
    });
});

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
