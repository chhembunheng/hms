<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::group(['prefix' => 'payment', 'as' => 'payment.', 'middleware' => ['auth', 'verified', 'abilities']], function () {

    // Payment History
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [PaymentController::class, 'history'])->name('index');
    });
});
