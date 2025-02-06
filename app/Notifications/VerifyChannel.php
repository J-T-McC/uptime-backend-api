<?php

namespace App\Notifications;

use App\Actions\CreateSignedVerifyChannelUrl;
use App\Enums\PagerDutySeverity;
use App\Models\Channel;
use App\Notifications\Channels\Discord\DiscordMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use NotificationChannels\PagerDuty\PagerDutyMessage;

class VerifyChannel extends Notification
{
    use Queueable;

    protected const string TITLE_TEXT = 'New Uptime Channel Created';

    protected const string ACTION_TEXT = 'Verify Channel';

    public function __construct(public readonly Channel $channel) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(AnonymousNotifiable $notifiable): array
    {
        return config('uptime-monitor.notifications.integrated-services');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(static::TITLE_TEXT)
            ->line($this->getMessageText())
            ->action(static::ACTION_TEXT, $this->getVerifyUrl());
    }

    public function toDiscord(): DiscordMessage
    {
        return (new DiscordMessage)
            ->success()
            ->title(static::TITLE_TEXT)
            ->description([
                $this->getMessageText(),
                $this->getVerifyUrl(),
            ])
            ->timestamp(Carbon::now());
    }

    public function toSlack(): SlackMessage
    {
        return (new SlackMessage)
            ->success()
            ->attachment(function (SlackAttachment $attachment) {
                $attachment
                    ->title(static::TITLE_TEXT)
                    ->content($this->getMessageText())
                    ->action(static::ACTION_TEXT, $this->getVerifyUrl())
                    ->timestamp(Carbon::now());
            });
    }

    public function toPagerDuty(mixed $notifiable): PagerDutyMessage
    {
        return PagerDutyMessage::create()
            ->setSource(config('app.spa_url'))
            ->setSeverity(PagerDutySeverity::INFO->value)
            ->setSummary($this->getMessageText())
            ->setTimestamp(Carbon::now())
            ->addCustomDetail('verify_url', $this->getVerifyUrl());
    }

    public function getMessageText(): string
    {
        $user = $this->channel->user()->firstOrFail();

        return sprintf('%s created a new uptime channel that needs verification.', $user->name);
    }

    public function getVerifyUrl(): string
    {
        $url = (new CreateSignedVerifyChannelUrl)->handle($this->channel);

        return str_replace(config('app.url'), config('app.spa_url'), $url);
    }
}
