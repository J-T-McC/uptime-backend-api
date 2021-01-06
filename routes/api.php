<?php

use Illuminate\Support\Facades\Route;

// arbitrary dummy route to accommodate any fortify redirects
// ex. email verification redirects the request to 'home' route defined in fortify config
Route::get('/home', function() {
   return response()->json([]);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/authenticated', function () {
        return new \App\Http\Resources\UserResource(auth()->user());
    });

    Route::middleware(['verified'])->group(function () {
        Route::resource('/monitors', \App\Http\Controllers\MonitorController::class);
        Route::resource('/channels', \App\Http\Controllers\ChannelController::class);
        Route::resource('/monitors-channels', \App\Http\Controllers\MonitorChannelController::class, ['PUT', 'PATCH']);

        Route::resource('/event-counts-trended', \App\Http\Controllers\EventCountsTrendedController::class, ['GET']);
        Route::resource('/event-counts-grouped', \App\Http\Controllers\EventCountsGroupedController::class, ['GET']);
        Route::resource('/latest-monitor-events', \App\Http\Controllers\LatestMonitorEventsController::class, ['GET']);
    });

});
