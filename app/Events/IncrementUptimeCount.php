<?php

namespace App\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\UptimeMonitor\Models\Monitor;

class IncrementUptimeCount implements ShouldQueue
{

    /** @var \App\Models\Monitor */
    public $monitor;

    /**
     * Create a new event instance.
     * @param Monitor $monitor
     * @return void
     */
    public function __construct(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }


}
