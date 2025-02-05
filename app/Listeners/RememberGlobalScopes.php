<?php

namespace App\Listeners;

use Illuminate\Database\Eloquent\Model;
use Laravel\Octane\Events\WorkerStarting;

class RememberGlobalScopes
{
    /**
     * Store all global scopes in the container.
     */
    public function handle(WorkerStarting $event): void
    {
        $event->app->instance('eloquent.scopes', Model::getAllGlobalScopes());
    }
}
