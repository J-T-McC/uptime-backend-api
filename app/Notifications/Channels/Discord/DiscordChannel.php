<?php

namespace App\Notifications\Channels\Discord;

use App\Models\User;
use App\Notifications\Channels\Exceptions\InvalidChanelException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;

class DiscordChannel
{
    /**
     * @param User|AnonymousNotifiable $notifiable
     * @param Notification $notification
     * @throws InvalidChanelException
     * @throws GuzzleException
     */
    public function send(User|AnonymousNotifiable $notifiable, Notification $notification): void
    {
        if (!$route = $notifiable->routeNotificationFor('discord')) {
            return;
        }

        if (!method_exists($notification, 'toDiscord')) {
            throw new InvalidChanelException();
        }

        (new Client())->post($route, [
            RequestOptions::JSON => $notification->toDiscord()->toArray(),
        ]);
    }
}
