<?php

namespace Database\Seeders;

use App\Models\MonitorDriver;
use Illuminate\Database\Seeder;

class MonitorDriverTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //online
        MonitorDriver::insertOrIgnore([
            'monitor_id' => 1,
            'type' => 'mail',
            'endpoint' => 'test@example.com'
        ]);

        //offline
        MonitorDriver::insertOrIgnore([
            'monitor_id' => 2,
            'type' => 'slack',
            'endpoint' => 'https://hooks.slack.com/services/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'
        ]);

        //not found
        MonitorDriver::insertOrIgnore([
            'monitor_id' => 3,
            'type' => 'mail',
            'endpoint' => 'test1@example.com'
        ]);

        MonitorDriver::insertOrIgnore([
            'monitor_id' => 3,
            'type' => 'mail',
            'endpoint' => 'test2@example.com'
        ]);

        MonitorDriver::insertOrIgnore([
            'monitor_id' => 3,
            'type' => 'slack',
            'endpoint' => 'https://hooks.slack.com/services/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'
        ]);


    }
}
