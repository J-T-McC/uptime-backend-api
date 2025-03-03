<?php

namespace App\Services\UptimeMonitor;

use Illuminate\Contracts\Events\Dispatcher;
use Spatie\UptimeMonitor\Notifications\EventHandler as SpatieEventHandler;

class EventHandler extends SpatieEventHandler
{
    /**
     * Override default spatie notification callback so we can dynamically generate notifications
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen($this->allEventClasses(), function ($event) {
            $notification = $this->determineNotification($event);

            if (! $notification) {
                return;
            }

            if ($notification->isStillRelevant()) {
                new NotificationDispatcher($notification);
            }
        });
    }
}
