<?php

namespace App\Services\MonitorNotification;

use Illuminate\Contracts\Events\Dispatcher;
use Spatie\UptimeMonitor\Notifications\EventHandler as SpatieEventHandler;

class EventHandler extends SpatieEventHandler
{

    /**
     * Override default spatie notification callback so we can dynamically generate notifications
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen($this->allEventClasses(), function ($event) {
            $notification = $this->determineNotification($event);

            if (!$notification) {
                return;
            }

            if ($notification->isStillRelevant()) {
                new MonitorNotificationService($notification);
            }
        });
    }

}
