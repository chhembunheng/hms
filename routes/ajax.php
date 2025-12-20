<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralController;

Route::group(['prefix' => 'ajax', 'as' => 'ajax.', 'middleware' => ['auth', 'verified', 'abilities']], function () {

    Route::get('/api/rooms', [GeneralController::class, 'getRooms'])->name('api.rooms');
    Route::get('/api/room-details', [GeneralController::class, 'getRoomDetails'])->name('api.room-details');

});
