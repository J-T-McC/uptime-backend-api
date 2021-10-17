<?php

namespace Tests\Integration\Notification;

use App\Notifications\CertificateCheckFailed;
use App\Notifications\CertificateCheckSucceeded;
use App\Notifications\CertificateExpiresSoon;
use App\Notifications\UptimeCheckFailed;
use App\Notifications\UptimeCheckRecovered;
use App\Notifications\UptimeCheckSucceeded;
use App\Services\UptimeMonitor\NotificationDispatcher;
use Database\Factories\ChannelFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

use Illuminate\Support\Facades\Config;

use Spatie\UptimeMonitor\MonitorCollection;
use Tests\TestCase;

/**
 * @see NotificationDispatcher
 */
class NotificationDispatcherTest extends TestCase
{

    use RefreshDatabase;

    private $channelTypes;

    public function setUp(): void
    {
        parent::setUp();

        $this->channelTypes = (new ChannelFactory())->channels();
    }

    /**
     * @test
     */
    public function notifies_all_channel_types_on_certificate_check_failed_event()
    {
        $this->checkAllChannelsForNotificationEvent(function($monitor, $type, $endpoint) {
            $monitor->url = static::BAD_SSL;
            $monitor->checkCertificate();
            Notification::assertSentTo(
                [(new AnonymousNotifiable())],
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
        Config::set('uptime-monitor.certificate_check.fire_expiring_soon_event_if_certificate_expires_within_days', 730);

        $this->checkAllChannelsForNotificationEvent(function($monitor, $type, $endpoint) {
            //override our faker url with one we know has a certificate installed
            $monitor->url = static::VALID_SSL;
            $monitor->checkCertificate();
            Notification::assertSentTo(
                [(new AnonymousNotifiable())],
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
        $this->checkAllChannelsForNotificationEvent(function($monitor, $type, $endpoint) {
            //override our faker url with one we know has a certificate installed
            $monitor->url = static::VALID_SSL;
            $monitor->checkCertificate();
            Notification::assertNothingSent();
        });
    }

    /**
     * @test
     */
    public function notifies_all_channel_types_on_uptime_check_failed_event()
    {
        $this->checkAllChannelsForNotificationEvent(function($monitor, $type, $endpoint) {
            //override our faker url with one we know will fail
            $monitor->url = static::UPTIME_FAIL;
            $collection = MonitorCollection::make([$monitor]);
            $collection->checkUptime();
            Notification::assertSentTo(
                [(new AnonymousNotifiable())],
                UptimeCheckFailed::class,
                fn ($notification, $channels, $notifiable) => $notifiable->routes[$type] === $endpoint
            );
        });
    }

    /**
     * Only dispatch succeeded upon recovery
     * @test
     */
    public function does_not_notify_any_channel_types_on_uptime_check_succeeded_event()
    {
        $this->checkAllChannelsForNotificationEvent(function($monitor, $type, $endpoint) {
            //override our faker url with one we know will succeed
            $monitor->url = static::UPTIME_SUCCEED;
            $collection = MonitorCollection::make([$monitor]);
            $collection->checkUptime();
            Notification::assertNothingSent();
        });
    }


    /**
     * @test
     */
    public function notifies_all_channel_types_on_uptime_check_recovered_event()
    {
        $this->checkAllChannelsForNotificationEvent(function($monitor,  $type, $endpoint) {
            $monitor->uptimeRequestFailed('(┛ಠ_ಠ)┛彡┻━┻');
            $monitor->uptimeCheckSucceeded('┬─┬ノ( º _ ºノ)');

            //override our faker url with one we know will succeed
            $monitor->url = static::UPTIME_FAIL;
            $collection = MonitorCollection::make([$monitor]);
            $collection->checkUptime();

            $monitor->url = static::UPTIME_SUCCEED;
            $collection = MonitorCollection::make([$monitor]);
            $collection->checkUptime();

            Notification::assertSentTo(
                [(new AnonymousNotifiable())],
                UptimeCheckRecovered::class,
                fn ($notification, $channels, $notifiable) => $notifiable->routes[$type] === $endpoint
            );
        });
    }


    /**
     * Generate a monitors with notification channels to test
     * @param $cb
     */
    public function checkAllChannelsForNotificationEvent($cb) {
        foreach ($this->channelTypes as $type => $endpoint) {

            Notification::fake();

            $monitor = \App\Models\Monitor::factory()->create();

            $channel = \App\Models\Channel::factory([
                'type' => $type,
                'endpoint' => $endpoint
            ])->create();

            Notification::assertNothingSent();

            $monitor->channels()->sync([$channel->id]);

            $cb($monitor, $type, $endpoint);
        }
    }


}
