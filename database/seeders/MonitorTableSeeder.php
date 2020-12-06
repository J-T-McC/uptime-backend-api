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
            'url' => 'http://httpstat.us/200',
            'uptime_check_interval_in_minutes' => 1
        ]);

        //timeout
        Monitor::insertOrIgnore([
            'user_id' => 1,
            'url' => 'http://httpstat.us/524',
            'uptime_check_interval_in_minutes' => 1
        ]);

        //not found
        Monitor::insertOrIgnore([
            'user_id' => 1,
            'url' => 'http://httpstat.us/404',
            'uptime_check_interval_in_minutes' => 1
        ]);
    }
}
