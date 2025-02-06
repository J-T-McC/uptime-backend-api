<?php

namespace App\Notifications;

use App\Actions\GetPagerDutyDedupKey;
use App\Enums\Category;
use App\Enums\PagerDutySeverity;
use App\Notifications\Channels\Discord\DiscordMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use NotificationChannels\PagerDuty\PagerDutyMessage;
use Spatie\UptimeMonitor\Notifications\Notifications\UptimeCheckRecovered as SpatieUptimeCheckRecovered;

class UptimeCheckRecovered extends SpatieUptimeCheckRecovered
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
            ->success()
            ->title($this->getMessageText())
            ->description([
                $this->getMessageText(),
            ])
            ->footer($this->getLocationDescription())
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
            ->setSummary($this->getMessageText())
            ->setTimestamp(Carbon::now())
            ->resolve();
    }
}
