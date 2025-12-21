<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\GeneralController;

Route::middleware(['auth', 'verified', 'abilities'])->group(function () {

    // Activity Logs
    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
    });

    // General AJAX routes
    Route::get('/api/rooms', [GeneralController::class, 'getRooms'])->name('api.rooms');
    Route::get('/api/room-details', [GeneralController::class, 'getRoomDetails'])->name('api.room-details');

});

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/rooms.php';
require __DIR__ . '/checkins.php';
require __DIR__ . '/ajax.php';

