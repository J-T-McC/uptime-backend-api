<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Spatie\UptimeMonitor\Notifications\Notifications\UptimeCheckSucceeded as SpatieUptimeCheckSucceeded;

class UptimeCheckSucceeded extends SpatieUptimeCheckSucceeded
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
     * @param  mixed  $notifiable
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
                $this->getMessageText()
            ])
            ->footer($this->getLocationDescription())
            ->timestamp(\Carbon\Carbon::now());
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
