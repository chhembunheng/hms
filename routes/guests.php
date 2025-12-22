<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guests\GuestController;
use App\Http\Controllers\Guests\GuestStayHistoryController;

Route::group(['prefix' => 'guests', 'as' => 'guests.', 'middleware' => ['auth', 'verified', 'abilities']], function () {

    // Guest List
    Route::prefix('list')->name('list.')->group(function () {
        Route::get('/', [GuestController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [GuestController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [GuestController::class, 'edit'])->name('edit');
        Route::get('/{id}/show', [GuestController::class, 'show'])->name('show');
        Route::delete('/{id}/delete', [GuestController::class, 'delete'])->name('delete');
        Route::match(['get', 'post'], '/{id}/blacklist', [GuestController::class, 'toggleBlacklist'])->name('blacklist');
        Route::match(['get', 'post'], '/{id}/notes', [GuestController::class, 'updateNotes'])->name('notes');
    });

});
