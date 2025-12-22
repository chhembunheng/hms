<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::group(['prefix' => 'checkout', 'as' => 'checkout.', 'middleware' => ['auth', 'verified', 'abilities']], function () {

    // Checkout & Payment
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/{id}/edit', [CheckoutController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [CheckoutController::class, 'delete'])->name('delete');
    });
});
