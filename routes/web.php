<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\GeneralController;

// Public routes for landing pages
Route::get('/', function () {
    return redirect('/en');
});

Route::prefix('{locale}')->where(['locale' => 'en|km'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/privacy-policy', function () {
        return view('welcome');
    })->name('privacy-policy');

    Route::get('/cookie-policy', function () {
        return view('welcome');
    })->name('cookie-policy');

    Route::get('/terms-condition', function () {
        return view('welcome');
    })->name('terms-condition');

    Route::get('/faq', function () {
        return view('welcome');
    })->name('faq');

    Route::get('/integrations', function () {
        return view('welcome');
    })->name('integrations');

    Route::get('/careers', function () {
        return view('welcome');
    })->name('careers');

    Route::get('/teams', function () {
        return view('welcome');
    })->name('teams');

    Route::get('/about', function () {
        return view('welcome');
    })->name('about');

    Route::get('/contact', function () {
        return view('welcome');
    })->name('contact');

    Route::get('/blogs', function () {
        return view('welcome');
    })->name('blogs');

    Route::get('/services', function () {
        return view('welcome');
    })->name('services');

    Route::get('/products', function () {
        return view('welcome');
    })->name('products');

    Route::get('/pricing', function () {
        return view('welcome');
    })->name('pricing');
});

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

