<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;

use Spatie\UptimeMonitor\Notifications\Notifications\CertificateCheckSucceeded as SpatieCertificateCheckSucceeded;

class CertificateCheckSucceeded extends SpatieCertificateCheckSucceeded
{
    use Queueable;

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
            ->success()
            ->title($this->getMessageText())
            ->description([
                "Expires {$this->getMonitor()->formattedCertificateExpirationDate('forHumans')}"
            ])
            ->footer($this->getMonitor()->certificate_issuer)
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
