<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillingController;

Route::group(['prefix' => 'billing', 'as' => 'billing.', 'middleware' => ['auth', 'verified', 'abilities']], function () {

    // Payment Invoice List
    Route::prefix('list')->name('list.')->group(function () {
        Route::get('/', [BillingController::class, 'index'])->name('index');
        Route::match(['get', 'post', 'patch'], '/{id}/edit', [BillingController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [BillingController::class, 'delete'])->name('delete');
    });

    // Invoice History
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [BillingController::class, 'history'])->name('index');
    });

    // Cancel Invoice
    Route::prefix('void')->name('void.')->group(function () {
        Route::get('/', [BillingController::class, 'voidInvoices'])->name('index');
        Route::match(['get', 'post', 'patch'], '/{id}/edit', [BillingController::class, 'editVoid'])->name('edit');
        Route::delete('/{id}/delete', [BillingController::class, 'deleteVoid'])->name('delete');
    });

    // Pending Cancel Invoice
    Route::prefix('pending-cancel-invoice')->name('pending-cancel-invoice.')->group(function () {
        Route::get('/', [BillingController::class, 'pendingCancelInvoices'])->name('index');
        Route::match(['get', 'post', 'patch'], '/{id}/edit', [BillingController::class, 'editPendingCancel'])->name('edit');
        Route::delete('/{id}/delete', [BillingController::class, 'deletePendingCancel'])->name('delete');
    });
});
