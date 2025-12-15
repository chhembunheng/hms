<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PlanController;

Route::group(['prefix' => 'frontends', 'as' => 'frontends.', 'middleware' => ['auth', 'verified', 'abilities', 'editor']], function () {

    // Plans Routes
    Route::prefix('plans')->name('plans.')->group(function () {
        Route::get('/', [PlanController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [PlanController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [PlanController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [PlanController::class, 'destroy'])->name('delete');
    });

});
