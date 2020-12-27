<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/authenticated', function () {
        return new \App\Http\Resources\UserResource(auth()->user());
    })->middleware();

    Route::middleware(['verified'])->group(function () {
        Route::resource('/monitors', \App\Http\Controllers\MonitorController::class);
        Route::resource('/channels', \App\Http\Controllers\ChannelController::class);
        Route::resource('/monitors-channels', \App\Http\Controllers\MonitorChannelController::class, ['PUT', 'PATCH']);

        Route::resource('/event-counts-trended', \App\Http\Controllers\EventCountsTrendedController::class, ['GET']);
        Route::resource('/event-counts-grouped', \App\Http\Controllers\EventCountsGroupedController::class, ['GET']);
        Route::resource('/latest-monitor-events', \App\Http\Controllers\LatestMonitorEventsController::class, ['GET']);
    });

});
