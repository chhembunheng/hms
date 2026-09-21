<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reports\DailyReportController;
use App\Http\Controllers\Reports\MonthlyReportController;
use App\Http\Controllers\Reports\GuestReportController;

Route::group(['prefix' => 'reports', 'as' => 'reports.', 'middleware' => ['auth', 'verified', 'abilities']], function () {

    // Daily Report
    Route::prefix('daily')->name('daily.')->group(function () {
        Route::get('/', [DailyReportController::class, 'index'])->name('index');
        Route::get('/print', [DailyReportController::class, 'print'])->name('print');
    });

    // Monthly Report
    Route::prefix('monthly')->name('monthly.')->group(function () {
        Route::get('/', [MonthlyReportController::class, 'index'])->name('index');
        Route::get('/print', [MonthlyReportController::class, 'print'])->name('print');
    });

    // Guest Report
    Route::prefix('guest')->name('guest.')->group(function () {
        Route::get('/', [GuestReportController::class, 'index'])->name('index');
        Route::get('/print', [GuestReportController::class, 'print'])->name('print');
    });

    // FPCS Foreigner Registration Report (Cambodia Immigration & Police)
    Route::prefix('fpcs')->name('fpcs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Reports\FPCSReportController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\Reports\FPCSReportController::class, 'export'])->name('export');
        Route::get('/print', [\App\Http\Controllers\Reports\FPCSReportController::class, 'print'])->name('print');
    });
});
