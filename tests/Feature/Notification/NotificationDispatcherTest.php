<?php

namespace Tests\Feature\Notification;

use App\Models\Channel;
use App\Models\Monitor;
use App\Notifications\AnonymousNotifiable;
use App\Notifications\CertificateCheckFailed;
use App\Notifications\CertificateExpiresSoon;
use App\Notifications\UptimeCheckFailed;
use App\Notifications\UptimeCheckRecovered;
use App\Services\UptimeMonitor\NotificationDispatcher;
use Database\Factories\ChannelFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Spatie\SslCertificate\SslCertificate;
use Spatie\UptimeMonitor\Events\CertificateCheckSucceeded;
use Spatie\UptimeMonitor\Events\UptimeCheckSucceeded;
use Spatie\UptimeMonitor\Helpers\Period;
use Tests\TestCase;

/**
 * @see NotificationDispatcher
 */
class NotificationDispatcherTest extends TestCase
{
    private array $channelTypes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->channelTypes = (new ChannelFactory)->channels();
    }

    /**
     * @test
     */
    public function notifies_all_channel_types_on_certificate_check_failed_event()
    {
        $this->checkAllChannelsForNotificationEvent(function ($monitor, $type, $endpoint) {
            $monitor->certificate_status = 'invalid';

            Event::dispatch(
                new \Spatie\UptimeMonitor\Events\CertificateCheckFailed(
                    $monitor,
                    new SslCertificate([])
                )
            );

            Notification::assertSentTo(
                [(new AnonymousNotifiable)],
                CertificateCheckFailed::class,
                fn ($notification, $channels, $notifiable) => $notifiable->routes[$type] === $endpoint
            );
        });
    }

    /**
     * @test
     */
    public function notifies_all_channel_types_on_certificate_expires_soon_event()
    {
        Config::set(
            'uptime-monitor.certificate_check.fire_expiring_soon_event_if_certificate_expires_within_days',
            730
        );

        $this->checkAllChannelsForNotificationEvent(function ($monitor, $type, $endpoint) {
            $monitor->certificate_status = 'valid';

            Event::dispatch(
                new \Spatie\UptimeMonitor\Events\CertificateExpiresSoon(
                    $monitor,
                    new SslCertificate([])
                )
            );

            Notification::assertSentTo(
                [(new AnonymousNotifiable)],
                CertificateExpiresSoon::class,
                fn ($notification, $channels, $notifiable) => $notifiable->routes[$type] === $endpoint
            );
        });
    }

    /**
     * @test
     */
    public function does_not_notify_any_channel_types_on_certificate_valid_event()
    {
        $this->checkAllChannelsForNotificationEvent(function ($monitor) {
            $monitor->certificate_status = 'valid';

            Event::dispatch(
                new CertificateCheckSucceeded(
                    $monitor,
                    new SslCertificate([])
                )
            );

            Notification::assertNothingSent();
        });
    }

    /**
     * @test
     */
    public function notifies_all_channel_types_on_uptime_check_failed_event()
    {
        $this->checkAllChannelsForNotificationEvent(function ($monitor, $type, $endpoint) {
            $monitor->uptime_status = 'down';

            Event::dispatch(
                new \Spatie\UptimeMonitor\Events\UptimeCheckFailed(
                    $monitor,
                    new Period(now()->subMinutes(5), now())
                )
            );

            Notification::assertSentTo(
                [(new AnonymousNotifiable)],
                UptimeCheckFailed::class,
                fn ($notification, $channels, $notifiable) => $notifiable->routes[$type] === $endpoint
            );
        });
    }

    /**
     * Only dispatch succeeded upon recovery
     *
     * @test
     */
    public function does_not_notify_any_channel_types_on_uptime_check_succeeded_event()
    {
        $this->checkAllChannelsForNotificationEvent(function ($monitor) {
            // override our faker url with one we know will succeed
            $monitor->uptime_status = 'up';

            Event::dispatch(
                new UptimeCheckSucceeded($monitor)
            );

            Notification::assertNothingSent();
        });
    }

    /**
     * @test
     */
    public function notifies_all_channel_types_on_uptime_check_recovered_event()
    {
        $this->checkAllChannelsForNotificationEvent(function ($monitor, $type, $endpoint) {
            $monitor->uptimeCheckFailed('(┛ಠ_ಠ)┛彡┻━┻');
            $monitor->uptimeCheckSucceeded('┬─┬ノ( º _ ºノ)');

            Event::dispatch(
                new \Spatie\UptimeMonitor\Events\UptimeCheckRecovered(
                    $monitor,
                    new Period(now()->subMinutes(5), now())
                )
            );

            Notification::assertSentTo(
                [(new AnonymousNotifiable)],
                UptimeCheckRecovered::class,
                fn ($notification, $channels, $notifiable) => $notifiable->routes[$type] === $endpoint
            );
        });
    }

    /**
     * @test
     */
    public function it_doesnt_notify_unverified_channels()
    {
        // Collect
        $monitor = Monitor::factory()->createQuietly();
        $channel = Channel::factory([
            'type' => 'mail',
            'verified' => false,
            'endpoint' => 'email@example.com',
        ])->createQuietly();

        $monitor->channels()->sync([$channel->id]);
        $event = new \Spatie\UptimeMonitor\Events\CertificateCheckFailed($monitor, new SslCertificate([]));
        $notification = new CertificateCheckFailed;
        $notification->setEvent($event);

        // Act
        new NotificationDispatcher($notification);

        // Assert
        Notification::assertNothingSent();
    }

    /**
     * Generate a monitors with notification channels to test
     */
    public function checkAllChannelsForNotificationEvent($cb)
    {
        foreach ($this->channelTypes as $type => $endpoint) {
            Notification::fake();

            $monitor = Monitor::factory()->createQuietly();

            $channel = Channel::factory([
                'type' => $type,
                'verified' => true,
                'endpoint' => $endpoint,
            ])->createQuietly();

            Notification::assertNothingSent();

            $monitor->channels()->sync([$channel->id]);

            $cb($monitor, $type, $endpoint);
        }
    }
}
