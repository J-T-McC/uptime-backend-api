<?php

namespace App\Services\Channels\Discord;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Notifications\Notification;

class DiscordChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!$route = $notifiable->routeNotificationFor('discord')) {
            return;
        }
        (new Client())->post($route, [
            RequestOptions::JSON => $notification->toDiscord()->toArray(),
        ]);
    }
}
