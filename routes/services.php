<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Services\AirportTransferController;
use App\Http\Controllers\Services\ServiceCatalogController;
use App\Http\Controllers\Services\RoomServiceController;

Route::group(['prefix' => 'services', 'as' => 'services.', 'middleware' => ['auth', 'verified', 'abilities']], function () {

    // SAI Airport Transfers & Transport Desk
    Route::prefix('transfers')->name('transfers.')->group(function () {
        Route::get('/', [AirportTransferController::class, 'index'])->name('index');
        Route::post('/store', [AirportTransferController::class, 'store'])->name('store');
        Route::post('/{id}/status', [AirportTransferController::class, 'updateStatus'])->name('status');
        Route::delete('/{id}/delete', [AirportTransferController::class, 'delete'])->name('delete');
    });

    // Tours & Services Catalog
    Route::prefix('catalog')->name('catalog.')->group(function () {
        Route::get('/', [ServiceCatalogController::class, 'index'])->name('index');
        Route::post('/store', [ServiceCatalogController::class, 'store'])->name('store');
        Route::delete('/{id}/delete', [ServiceCatalogController::class, 'delete'])->name('delete');
    });

    // Room Folio Charges (Post Tour, Laundry, Spa, Extra services to staying guest)
    Route::prefix('room-charges')->name('room-services.')->group(function () {
        Route::get('/', [RoomServiceController::class, 'index'])->name('index');
        Route::post('/store', [RoomServiceController::class, 'store'])->name('store');
        Route::delete('/{id}/delete', [RoomServiceController::class, 'delete'])->name('delete');
    });
});
