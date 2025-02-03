<?php

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\EventCountsGroupedController;
use App\Http\Controllers\EventCountsTrendedController;
use App\Http\Controllers\LatestMonitorEventsController;
use App\Http\Controllers\MonitorChannelController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\VerifyChannelController;
use App\Http\Middleware\ApplyOwnerScope;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;

// arbitrary dummy route to accommodate any fortify redirects
// ex. email verification redirects the request to 'home' route defined in fortify config
Route::get('/home', function () {
    return response()->json([]);
})->name('api.home');

Route::middleware(['auth:sanctum', ApplyOwnerScope::class])->group(function () {
    Route::get('/authenticated', function () {
        return new UserResource(auth()->user());
    });

    Route::middleware(['verified'])->group(function () {
        Route::apiResource('/monitors', MonitorController::class);
        Route::apiResource('/channels', ChannelController::class);
        Route::put('/monitors-channels/{monitor}', [MonitorChannelController::class, 'update'])->name('monitors-channels.update');

        Route::get('/event-counts-trended', [EventCountsTrendedController::class, 'index'])->name('event-counts-trended.index');
        Route::get('/event-counts-trended/{monitor}', [EventCountsTrendedController::class, 'show'])->name('event-counts-trended.show');
        Route::get('/event-counts-grouped', [EventCountsGroupedController::class, 'index'])->name('event-counts-grouped.index');
        Route::get('/event-counts-grouped/{monitor}', [EventCountsGroupedController::class, 'show'])->name('event-counts-grouped.show');

        Route::get('/latest-monitor-events', [LatestMonitorEventsController::class, 'index'])->name('latest-monitor-events.index');
        Route::get('/latest-monitor-events/{monitor}', [LatestMonitorEventsController::class, 'show'])->name('latest-monitor-events.show');

        Route::get('/channel/verify/{channel}/{endpoint}', [VerifyChannelController::class, '__invoke'])->name('verification.channel');
    });
});
