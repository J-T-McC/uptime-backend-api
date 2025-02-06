<?php

namespace App\Actions;

use App\Enums\Category;
use Spatie\UptimeMonitor\Models\Monitor;

class GetPagerDutyDedupKey
{
    public function handle(Monitor $monitor, Category $category): string
    {
        $prefix = match ($category) {
            Category::UPTIME => 'event-uptime-',
            Category::CERTIFICATE => 'event-certificate-',
        };

        /* @phpstan-ignore-next-line */
        return sprintf('%s%s', $prefix, $monitor->hash_id);
    }
}
