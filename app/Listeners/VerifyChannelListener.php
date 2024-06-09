<?php

namespace App\Listeners;

use App\Events\ChannelCreated;
use App\Jobs\DispatchNotification;
use App\Notifications\AnonymousNotifiable;
use App\Notifications\VerifyChannel;

class VerifyChannelListener
{
    /**
     * Handle the event.
     */
    public function handle(ChannelCreated $event): void
    {
        $channel = $event->channel;
        $notifiable = new AnonymousNotifiable();
        $notifiable->route($channel->type, $channel->endpoint);
        $notification = new VerifyChannel($channel);
        DispatchNotification::dispatch($notifiable, $notification);
    }
}
