<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthenticatedTestCase;

/**
 * @see \App\Http\Controllers\ChannelController
 */
class ChannelControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
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
     */
    public function it_lists_channels()
    {

        $response = $this->getJson(route('channels.index'));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_shows_channels()
    {

        $channel = Channel::factory()->create();

        $response = $this->getJson(route('channels.show', $channel));

        $response->assertOk();
    }

    /**
     * @test
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
