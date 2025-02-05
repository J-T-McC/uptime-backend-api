<?php

namespace App\Listeners;

use Illuminate\Database\Eloquent\Model;
use Laravel\Octane\Events\RequestTerminated;

class FlushGlobalScopes
{
    /**
     * Reset global scopes to their default state.
     * This is required to prevent octane from leaking global scopes between requests.
     */
    public function handle(RequestTerminated $event): void
    {
        $scopes = [];

        if ($event->app->bound('eloquent.scopes')) {
            $scopes = $event->app->make('eloquent.scopes');
        }

        Model::setAllGlobalScopes($scopes);
    }
}
