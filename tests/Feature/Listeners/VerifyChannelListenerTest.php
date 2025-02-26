<?php

namespace Listeners;

use App\Events\ChannelCreated;
use App\Events\ChannelUpdated;
use App\Listeners\VerifyChannelListener;
use App\Models\Channel;
use App\Notifications\AnonymousNotifiable;
use App\Notifications\VerifyChannel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(VerifyChannelListener::class)]
class VerifyChannelListenerTest extends TestCase
{
    public function test_it_subscribes_to_channel_events(): void
    {
        Event::fake();
        Event::assertListening(ChannelCreated::class, VerifyChannelListener::class);
        Event::assertListening(ChannelUpdated::class, VerifyChannelListener::class);
    }

    /**
     * @see VerifyChannelListener::handle
     */
    #[DataProvider('typeProvider')]
    public function test_it_dispatches_notification_to_expected_channel_upon_channel_created_event(string $channelType): void
    {
        // Collect
        Notification::fake();
        $channel = Channel::factory()->createQuietly([
            'verified' => false,
            'type' => $channelType,
        ]);
        $event = new ChannelCreated($channel);
        $listener = new VerifyChannelListener;

        // Act
        $listener->handle($event);

        // Assert
        Notification::assertSentTo(
            notifiable: [(new AnonymousNotifiable)],
            notification: VerifyChannel::class,
            callback: function ($notification, $channels, $notifiable) use ($channelType, $channel) {
                return count($notifiable->routes) === 1 && $notifiable->routes[$channelType] === $channel->endpoint;
            }
        );
    }

    /**
     * @see VerifyChannelListener::handle
     */
    #[DataProvider('typeProvider')]
    public function test_it_dispatches_notification_to_expected_channel_upon_channel_updated_event(string $channelType): void
    {
        // Collect
        Notification::fake();
        $channel = Channel::factory()->createQuietly([
            'verified' => true,
            'type' => $channelType,
        ]);
        // make the endpoint property dirty
        $channel->endpoint = 'https://example.com/new/endpoint';
        $event = new ChannelUpdated($channel);
        $listener = new VerifyChannelListener;

        // Act
        $listener->handle($event);

        // Assert
        $this->assertFalse($channel->refresh()->verified);
        Notification::assertSentTo(
            notifiable: [(new AnonymousNotifiable)],
            notification: VerifyChannel::class,
            callback: function ($notification, $channels, $notifiable) use ($channelType, $channel) {
                return count($notifiable->routes) === 1 && $notifiable->routes[$channelType] === $channel->endpoint;
            }
        );
    }

    /**
     * @see VerifyChannelListener::handle
     */
    #[DataProvider('typeProvider')]
    public function test_it_doesnt_dispatch_notification_upon_channel_updated_event_when_endpoint_unchanged(
        string $channelType
    ): void {
        // Collect
        Notification::fake();
        $channel = Channel::factory()->createQuietly([
            'verified' => true,
            'type' => $channelType,
        ]);
        // make the endpoint property dirty
        $event = new ChannelUpdated($channel);
        $listener = new VerifyChannelListener;

        // Act
        $listener->handle($event);

        // Assert
        $this->assertTrue($channel->refresh()->verified);
        Notification::assertNothingSent();
    }

    public static function typeProvider(): array
    {
        return [
            ['mail'],
            ['slack'],
            ['discord'],
        ];
    }
}
