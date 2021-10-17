<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedTestCase;

/**
 * @coversDefaultClass  \App\Http\Controllers\ChannelController
 */
class ChannelControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers ::destroy
     */
    public function it_deletes_channels()
    {
        $channel = Channel::factory()->create();

        $response = $this->deleteJson(route('channels.destroy', $channel));

        $response->assertNoContent();
        $this->assertDeleted($channel);
    }

    /**
     * @test
     * @covers ::index
     */
    public function it_lists_channels()
    {
        $response = $this->getJson(route('channels.index'));

        $response->assertOk();
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

        $response = $this->put(route('channels.update', $channel), [
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail,
            'description' => 'Test Update',
        ]);

        $response->assertOk();
    }
}
