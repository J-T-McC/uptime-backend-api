<?php

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\EventCountsGroupedController;
use App\Http\Controllers\EventCountsTrendedController;
use App\Http\Controllers\LatestMonitorEventsController;
use App\Http\Controllers\MonitorChannelController;
use App\Http\Controllers\MonitorController;
use Illuminate\Support\Facades\Route;

// arbitrary dummy route to accommodate any fortify redirects
// ex. email verification redirects the request to 'home' route defined in fortify config
Route::get('/home', function () {
    return response()->json([]);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/authenticated', function () {
        return new \App\Http\Resources\UserResource(auth()->user());
    });

    Route::middleware(['verified'])->group(function () {
        Route::apiResource('/monitors', MonitorController::class);
        Route::apiResource('/channels', ChannelController::class);
        Route::put('/monitors-channels/{monitor}', [MonitorChannelController::class, 'associate']);

        Route::get('/event-counts-trended', [EventCountsTrendedController::class, 'index']);
        Route::get('/event-counts-trended/{monitor}', [EventCountsTrendedController::class, 'show']);
        Route::get('/event-counts-grouped', [EventCountsGroupedController::class, 'index']);
        Route::get('/event-counts-grouped/{monitor}', [EventCountsGroupedController::class, 'show']);

        Route::apiResource('/latest-monitor-events', LatestMonitorEventsController::class, ['GET']);
    });
});
