<?php

namespace App\Listeners;

use App\Events\IncrementUptimeCount;
use App\Models\MonitorUptimeEventCount;

class IncrementUptimeCountListener
{

    private $validColumns = [
      'up',
      'down',
      'recovered'
    ];

    /**
     * Handle the event.
     *
     * @param  IncrementUptimeCount  $event
     * @return void
     */
    public function handle(IncrementUptimeCount $event)
    {
        if(in_array($event->monitor->uptime_status, $this->validColumns)) {
            $monitorDateFilters = MonitorUptimeEventCount::getDateFilterValues();
            $monitorCounts = MonitorUptimeEventCount::firstOrCreate(array_merge(
                ['monitor_id' => $event->monitor->id, 'user_id' => $event->monitor->user_id],
                $monitorDateFilters
            ));
            $monitorCounts->increment($event->monitor->uptime_status);
        }
    }
}
