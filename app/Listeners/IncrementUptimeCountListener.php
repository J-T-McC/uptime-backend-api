<?php

namespace App\Listeners;

use App\Events\IncrementUptimeCount;
use App\Models\MonitorUptimeEventCount;
use Carbon\Carbon;

class IncrementUptimeCountListener
{
    private const array VALID_COLUMNS = [
        'up',
        'down',
        'recovered',
    ];

    public function handle(IncrementUptimeCount $event): void
    {
        if (in_array($event->monitor->uptime_status, self::VALID_COLUMNS)) {
            $monitorCounts = MonitorUptimeEventCount::firstOrCreate([
                'monitor_id' => $event->monitor->id,
                'user_id' => $event->monitor->user_id,
                'filter_date' => Carbon::now('UTC')->format('Y-m-d'),
            ]);

            $monitorCounts->increment($event->monitor->uptime_status);
        }
    }
}
