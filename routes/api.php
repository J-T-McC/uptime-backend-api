<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/authenticated', function () {
        return ['message' => 'authenticated'];
    });
    Route::resource('/monitors', \App\Http\Controllers\MonitorController::class);
    Route::resource('/channels', \App\Http\Controllers\ChannelController::class);
});
