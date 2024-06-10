<?php

namespace App\Listeners;

use App\Events\ChannelCreated;
use App\Events\ChannelUpdated;
use App\Jobs\DispatchNotification;
use App\Notifications\AnonymousNotifiable;
use App\Notifications\VerifyChannel;

class VerifyChannelListener
{
    /**
     * Handle the event.
     */
    public function handle(ChannelCreated|ChannelUpdated $event): void
    {
        $channel = $event->channel;

        if ($channel->isDirty(['endpoint'])) {
            // force reverification upon endpoint update
            $channel->verified = false;
            $channel->saveQuietly();
        }

        if ($channel->verified) {
            return;
        }

        $notification = new VerifyChannel($channel);
        $notifiable = new AnonymousNotifiable();
        $notifiable->route($channel->type, $channel->endpoint);

        DispatchNotification::dispatch($notifiable, $notification);
    }
}
