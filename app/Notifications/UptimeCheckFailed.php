<?php

namespace App\Notifications;

use App\Services\Channels\Discord\DiscordMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Spatie\UptimeMonitor\Notifications\Notifications\UptimeCheckFailed as SpatieUptimeCheckFailed;

class UptimeCheckFailed extends SpatieUptimeCheckFailed
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
            ->error()
            ->title($this->getMessageText())
            ->description([
                $this->getMonitor()->uptime_check_failure_reason
            ])
            ->footer($this->getLocationDescription())
            ->timestamp(Carbon::now());
    }
}
