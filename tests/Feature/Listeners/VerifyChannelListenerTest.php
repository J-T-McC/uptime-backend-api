<?php

namespace Listeners;

use App\Events\ChannelCreated;
use App\Listeners\VerifyChannelListener;
use App\Models\Channel;
use App\Notifications\AnonymousNotifiable;
use App\Notifications\VerifyChannel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @see VerifyChannelListener
 */
class VerifyChannelListenerTest extends TestCase
{
    /**
     * @test
     */
    public function it_subscribes_to_channel_created_event(): void
    {
        Event::fake();
        Event::assertListening(ChannelCreated::class, VerifyChannelListener::class);
    }

    /**
     * @test
     * @testWith
     * ["mail"]
     * ["slack"]
     * ["discord"]
     */
    public function it_dispatches_notification_to_expected_channel(string $channelType): void
    {
        // Collect
        Notification::fake();
        $channel = Channel::factory()->create([
            'verified' => false,
            'type' => $channelType,
        ]);
        $event = new ChannelCreated($channel);
        $listener = new VerifyChannelListener();

        // Act
        $listener->handle($event);

        // Assert
        Notification::assertSentTo(
            notifiable: [(new AnonymousNotifiable())],
            notification: VerifyChannel::class,
            callback: function ($notification, $channels, $notifiable) use ($channelType, $channel) {
                return count($notifiable->routes) === 1 && $notifiable->routes[$channelType] === $channel->endpoint;
            }
        );
    }
}
