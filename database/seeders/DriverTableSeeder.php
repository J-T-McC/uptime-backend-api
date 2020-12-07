<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Monitor;
use Illuminate\Database\Seeder;

class DriverTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $mailDriver1 = Driver::create([
            'type' => 'mail',
            'user_id' => 1,
            'endpoint' => 'test1@example.com'
        ]);

        $mailDriver2 = Driver::create([
            'type' => 'mail',
            'user_id' => 1,
            'endpoint' => 'test2@example.com'
        ]);

        $slackDriver = Driver::create([
            'type' => 'slack',
            'user_id' => 1,
            'endpoint' => env('SLACK_WEBHOOK')
        ]);

        $online =  Monitor::find(1);
        $offline =  Monitor::find(2);
        $notFound =  Monitor::find(3);
        $sslExpired =  Monitor::find(4);

        $online->drivers()->attach($mailDriver1);

        $offline->drivers()->attach($slackDriver);

        $notFound->drivers()->attach($mailDriver1);
        $notFound->drivers()->attach($mailDriver2);
        $notFound->drivers()->attach($slackDriver);

        $sslExpired->drivers()->attach($mailDriver1);
        $sslExpired->drivers()->attach($slackDriver);

    }
}
