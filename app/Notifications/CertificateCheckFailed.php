<?php

namespace App\Notifications;

use App\Services\Channels\Discord\DiscordMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Spatie\UptimeMonitor\Notifications\Notifications\CertificateCheckFailed as SpatieCertificateCheckFailed;

class CertificateCheckFailed extends SpatieCertificateCheckFailed
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
                $this->getMonitor()->certificate_check_failure_reason ?? 'No reason provided',
            ])
            ->footer($this->getMonitor()->certificate_issuer ?? '')
            ->timestamp(Carbon::now());
    }
}
