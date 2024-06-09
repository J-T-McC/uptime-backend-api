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
        $notification = new VerifyChannel($channel);

        $notifiable = new AnonymousNotifiable();
        $notifiable->route($channel->type, $channel->endpoint);

        DispatchNotification::dispatch($notifiable, $notification);
    }
}
