<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\UptimeMonitor\Models\Monitor;

class MonitorTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // online
        Monitor::query()->insertOrIgnore([
            'user_id' => 1,
            'url' => 'https://httpstat.us/200',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 1,
        ]);

        // timeout
        Monitor::query()->insertOrIgnore([
            'user_id' => 1,
            'url' => 'https://httpstat.us/524',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 0,
        ]);

        // not found
        Monitor::query()->insertOrIgnore([
            'user_id' => 1,
            'url' => 'https://httpstat.us/404',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 0,
        ]);

        // expired ssl
        Monitor::query()->insertOrIgnore([
            'user_id' => 1,
            'url' => 'https://expired.badssl.com/',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 1,
        ]);

        // online, user 2
        Monitor::query()->insertOrIgnore([
            'user_id' => 2,
            'url' => 'https://httpstat.us/200',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 0,
        ]);

        // online, user 2
        Monitor::query()->insertOrIgnore([
            'user_id' => 2,
            'url' => 'https://httpstat.us/404',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 0,
        ]);
    }
}
