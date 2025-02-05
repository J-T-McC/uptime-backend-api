<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Monitor;
use Illuminate\Database\Seeder;

class ChannelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $mailChannel1 = Channel::create([
            'type' => 'mail',
            'user_id' => 1,
            'endpoint' => 'test1@example.com',
            'description' => 'Primary ticket inbox',
        ]);

        $mailChannel2 = Channel::create([
            'type' => 'mail',
            'user_id' => 1,
            'endpoint' => 'test2@example.com',
            'description' => 'Personal inbox',
        ]);

        $slackChannel = Channel::create([
            'type' => 'slack',
            'user_id' => 1,
            'endpoint' => env('SLACK_WEBHOOK'),
            'description' => 'Dev team slack room',
        ]);

        $discordChannel = Channel::create([
            'type' => 'discord',
            'user_id' => 1,
            'endpoint' => env('DISCORD_URL'),
            'description' => 'My alert channel on my gaming discord server',
        ]);

        $online = Monitor::find(1);
        $offline = Monitor::find(2);
        $notFound = Monitor::find(3);
        $sslExpired = Monitor::find(4);

        $online->channels()->attach($mailChannel1);

        $offline->channels()->attach($mailChannel1);
        $offline->channels()->attach($slackChannel);
        $offline->channels()->attach($discordChannel);

        $notFound->channels()->attach($mailChannel1);
        $notFound->channels()->attach($mailChannel2);
        $notFound->channels()->attach($slackChannel);
        $notFound->channels()->attach($discordChannel);

        $sslExpired->channels()->attach($mailChannel1);
        $sslExpired->channels()->attach($slackChannel);
        $sslExpired->channels()->attach($discordChannel);

    }
}
