<?php

namespace App\Notifications;

use App\Actions\GetPagerDutyDedupKey;
use App\Enums\Category;
use App\Enums\PagerDutySeverity;
use App\Notifications\Channels\Discord\DiscordMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use NotificationChannels\PagerDuty\PagerDutyMessage;
use Spatie\UptimeMonitor\Notifications\Notifications\CertificateCheckFailed as SpatieCertificateCheckFailed;

class CertificateCheckFailed extends SpatieCertificateCheckFailed
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        return config('uptime-monitor.notifications.integrated-services');
    }

    public function toDiscord(): DiscordMessage
    {
        return (new DiscordMessage)
            ->error()
            ->title($this->getMessageText())
            ->description([
                $this->getMonitor()->certificate_check_failure_reason ?? 'no reason provided',
            ])
            ->footer('')
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
            ->setSeverity(PagerDutySeverity::CRITICAL->value)
            ->setSummary($this->getMessageText())
            ->addCustomDetail('failure_reason', $this->getMonitor()->certificate_check_failure_reason ?? 'no reason provided')
            ->addCustomDetail('certificate_issuer', $this->getMonitor()->certificate_issuer ?? '');
    }
}
