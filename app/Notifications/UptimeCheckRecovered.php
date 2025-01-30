<?php

namespace App\Notifications;

use App\Notifications\Channels\Discord\DiscordMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Spatie\UptimeMonitor\Notifications\Notifications\UptimeCheckRecovered as SpatieUptimeCheckRecovered;

class UptimeCheckRecovered extends SpatieUptimeCheckRecovered
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return config('uptime-monitor.notifications.integrated-services');
    }

    public function toDiscord(): DiscordMessage
    {
        return (new  DiscordMessage())
            ->success()
            ->title($this->getMessageText())
            ->description([
                $this->getMessageText()
            ])
            ->footer($this->getLocationDescription())
            ->timestamp(Carbon::now());
    }
}
