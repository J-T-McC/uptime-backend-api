<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\ChannelController;
use App\Models\Channel;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\AuthenticatedTestCase;

#[CoversClass(ChannelController::class)]
class ChannelControllerTest extends AuthenticatedTestCase
{
    use WithFaker;

    /**
     * @see ChannelController::destroy
     */
    public function test_it_deletes_channels(): void
    {
        $channel = Channel::factory()->createQuietly(['user_id' => $this->testUser->id]);

        $response = $this->deleteJson(route('channels.destroy', $channel));

        $response->assertNoContent();
        $this->assertModelMissing($channel);
    }

    /**
     * @see ChannelController::index
     */
    public function test_it_lists_channels(): void
    {
        Channel::factory()->count(10)->createQuietly(['user_id' => $this->testUser->id]);

        $response = $this->getJson(route('channels.index'));

        $response->assertOk();
        $this->assertResponseCollectionJson($response, 'channel.json');
    }

    /**
     * @see ChannelController::show
     */
    public function test_it_shows_channels(): void
    {
        $channel = Channel::factory()->createQuietly();

        $response = $this->getJson(route('channels.show', $channel));

        $response->assertOk();
        $this->assertResponseJson($response, 'channel.json');
    }

    /**
     * @see ChannelController::store
     */
    public function test_it_stores_channels(): void
    {
        $response = $this->postJson(route('channels.store'), [
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail,
        ]);

        $response->assertCreated();
    }

    /**
     * @see ChannelController::update
     */
    public function test_it_updates_channels(): void
    {
        $channel = Channel::factory()->createQuietly();

        $response = $this->putJson(route('channels.update', $channel), [
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail,
            'description' => 'Test Update',
        ]);

        $response->assertOk();
    }
}
