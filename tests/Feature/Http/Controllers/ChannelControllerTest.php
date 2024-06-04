<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthenticatedTestCase;

/**
 * @coversDefaultClass  \App\Http\Controllers\ChannelController
 */
class ChannelControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     * @covers ::destroy
     */
    public function it_deletes_channels()
    {
        $channel = Channel::factory()->create(['user_id' => $this->testUser->id]);

        $response = $this->deleteJson(route('channels.destroy', $channel));

        $response->assertNoContent();
        $this->assertModelMissing($channel);
    }

    /**
     * @test
     * @covers ::index
     */
    public function it_lists_channels()
    {
        Channel::factory()->count(10)->create(['user_id' => $this->testUser->id]);

        $response = $this->getJson(route('channels.index'));

        $response->assertOk();
        $this->assertResponseCollectionJson($response, 'channel.json');
    }

    /**
     * @test
     * @covers ::show
     */
    public function it_shows_channels()
    {
        $channel = Channel::factory()->create();

        $response = $this->getJson(route('channels.show', $channel));

        $response->assertOk();
        $this->assertResponseJson($response, 'channel.json');
    }

    /**
     * @test
     * @covers ::store
     */
    public function it_stores_channels()
    {
        $response = $this->postJson(route('channels.store'), [
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail
        ]);

        $response->assertCreated();
    }

    /**
     * @test
     * @covers ::update
     */
    public function it_updates_channels()
    {
        $channel = Channel::factory()->create();

        $response = $this->putJson(route('channels.update', $channel), [
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail,
            'description' => 'Test Update',
        ]);

        $response->assertOk();
    }
}
