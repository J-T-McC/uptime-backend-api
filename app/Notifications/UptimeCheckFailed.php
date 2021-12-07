<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Spatie\UptimeMonitor\Notifications\Notifications\UptimeCheckFailed as SpatieUptimeCheckFailed;

class UptimeCheckFailed extends SpatieUptimeCheckFailed
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return config('uptime-monitor.notifications.integrated-services');
    }

    public function toDiscord()
    {
        return (new  \App\Services\Channels\Discord\DiscordMessage())
            ->error()
            ->title($this->getMessageText())
            ->description([
                $this->getMonitor()->uptime_check_failure_reason
            ])
            ->footer($this->getLocationDescription())
            ->timestamp(\Carbon\Carbon::now());
    }


    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
