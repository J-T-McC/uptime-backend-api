<?php

namespace App\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\UptimeMonitor\Models\Monitor;

class IncrementUptimeCount implements ShouldQueue
{
    public Monitor $monitor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }
}
