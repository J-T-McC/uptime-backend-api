<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\UptimeMonitor\Models\Monitor;

class MonitorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //online
        Monitor::insertOrIgnore([
            'user_id' => 1,
            'url' => 'https://httpstat.us/200',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 1
        ]);

        //timeout
        Monitor::insertOrIgnore([
            'user_id' => 1,
            'url' => 'https://httpstat.us/524',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 0
        ]);

        //not found
        Monitor::insertOrIgnore([
            'user_id' => 1,
            'url' => 'https://httpstat.us/404',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 0
        ]);

        //expired ssl
        Monitor::insertOrIgnore([
            'user_id' => 1,
            'url' => 'https://expired.badssl.com/',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 1
        ]);

        //online, user 2
        Monitor::insertOrIgnore([
            'user_id' => 2,
            'url' => 'https://httpstat.us/200',
            'uptime_check_interval_in_minutes' => 1,
            'certificate_check_enabled' => 0
        ]);


    }
}
