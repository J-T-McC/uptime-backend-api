<?php

namespace App\Notifications;

use App\Actions\GetPagerDutyDedupKey;
use App\Enums\Category;
use App\Enums\PagerDutySeverity;
use App\Notifications\Channels\Discord\DiscordMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use NotificationChannels\PagerDuty\PagerDutyMessage;
use Spatie\UptimeMonitor\Notifications\Notifications\CertificateExpiresSoon as SpatieCertificateExpiresSoon;

class CertificateExpiresSoon extends SpatieCertificateExpiresSoon
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return config('uptime-monitor.notifications.integrated-services');
    }

    public function toDiscord(): DiscordMessage
    {
        return (new DiscordMessage)
            ->error()
            ->title($this->getMessageText())
            ->description([
                "Expires {$this->getMonitor()->formattedCertificateExpirationDate('forHumans')}",
            ])
            ->footer($this->getMonitor()->certificate_issuer ?? '')
            ->timestamp(Carbon::now());
    }

    public function toPagerDuty(mixed $notifiable): PagerDutyMessage
    {
        $dedupKey = app(GetPagerDutyDedupKey::class)->handle(
            monitor: $this->getMonitor(),
            category: Category::CERTIFICATE
        );

        return PagerDutyMessage::create()
            ->setDedupKey($dedupKey)
            ->setTimestamp(Carbon::now())
            ->setSource($this->getMonitor()->url ?? config('app.url'))
            ->setSeverity(PagerDutySeverity::INFO->value)
            ->setSummary("SSL certificate for {$this->getMonitor()->url} expires soon")
            ->addCustomDetail('message', "Expires {$this->getMonitor()->formattedCertificateExpirationDate('forHumans')}")
            ->addCustomDetail('certificate_issuer', $this->getMonitor()->certificate_issuer ?? '');
    }
}
