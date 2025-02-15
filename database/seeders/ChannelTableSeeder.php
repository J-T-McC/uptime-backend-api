<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Monitor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChannelTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mailChannel1 = Channel::query()->firstOrCreate([
            'type' => 'mail',
            'user_id' => 1,
            'endpoint' => 'test1@example.com',
            'description' => 'Primary ticket inbox',
            'verified' => 1,
        ]);

        $mailChannel2 = Channel::query()->firstOrCreate([
            'type' => 'mail',
            'user_id' => 1,
            'endpoint' => 'test2@example.com',
            'description' => 'Personal inbox',
            'verified' => 1,
        ]);

        $slackChannel = Channel::query()->firstOrCreate([
            'type' => 'slack',
            'user_id' => 1,
            'endpoint' => env('SLACK_WEBHOOK'),
            'description' => 'Dev team slack room',
        ]);

        $discordChannel = Channel::query()->firstOrCreate([
            'type' => 'discord',
            'user_id' => 1,
            'endpoint' => env('DISCORD_URL'),
            'description' => 'My alert channel on my gaming discord server',
        ]);

        $online = Monitor::query()->find(1);
        $offline = Monitor::query()->find(2);
        $notFound = Monitor::query()->find(3);
        $sslExpired = Monitor::query()->find(4);

        $online->channels()->sync($mailChannel1);

        $offline->channels()->sync([
            $mailChannel1,
            $slackChannel,
            $discordChannel,
        ]);

        $notFound->channels()->sync([
            $mailChannel1,
            $mailChannel2,
            $slackChannel,
            $discordChannel,
        ]);

        $sslExpired->channels()->sync([
            $mailChannel1,
            $slackChannel,
            $discordChannel,
        ]);
    }
}
