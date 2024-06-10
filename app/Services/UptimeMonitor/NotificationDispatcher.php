<?php

namespace App\Services\UptimeMonitor;

use App\Jobs\DispatchNotification;
use App\Models\Monitor;
use App\Models\Channel;
use App\Notifications\AnonymousNotifiable;
use App\Exceptions\UndefinedPropertyException;
use Illuminate\Notifications\Notification;

/**
 * @property-read Monitor $monitor
 * @property-read Channel $channels
 */
class NotificationDispatcher
{
    public function __construct(private readonly Notification $notification)
    {
        $this->dispatchNotifications();
    }

    /**
     * @param string $name
     * @return mixed
     * @throws UndefinedPropertyException
     */
    public function __get(string $name)
    {
        return match ($name) {
            /* @phpstan-ignore-next-line */
            'monitor' => $this->notification->event->monitor,
            'channels' => $this->monitor->verifiedChannels,
            default => throw new UndefinedPropertyException(),
        };
    }

    private function dispatchNotifications(): void
    {
        // Dispatch independently to accommodate multiples of the same channel
        $this->channels->each(function (Channel $channel) {
            $notifiable = new AnonymousNotifiable();
            $notifiable->route($channel->type, $channel->endpoint);

            DispatchNotification::dispatch($notifiable, $this->notification);
        });
    }
}
