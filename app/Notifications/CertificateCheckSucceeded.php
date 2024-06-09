<?php

namespace App\Notifications;

use App\Services\Channels\Discord\DiscordMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Spatie\UptimeMonitor\Notifications\Notifications\CertificateCheckSucceeded as SpatieCertificateCheckSucceeded;

class CertificateCheckSucceeded extends SpatieCertificateCheckSucceeded
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
                "Expires {$this->getMonitor()->formattedCertificateExpirationDate('forHumans')}"
            ])
            ->footer($this->getMonitor()->certificate_issuer)
            ->timestamp(Carbon::now());
    }
}
