<?php

namespace App\Http\Middleware;

use App\Models\Channel;
use App\Models\Monitor;
use App\Models\MonitorEvent;
use App\Models\MonitorUptimeEventCount;
use App\Models\Scopes\OwnerScope;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyOwnerScope
{
    /**
     * The Eloquent models that should have the owner scope applied.
     *
     * @var array<class-string>
     */
    protected array $models = [
        Monitor::class,
        Channel::class,
        MonitorEvent::class,
        MonitorUptimeEventCount::class,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // scope the authenticated user to their own resources
        foreach ($this->models as $model) {
            $model::addGlobalScope(new OwnerScope);
        }

        return $next($request);
    }
}
