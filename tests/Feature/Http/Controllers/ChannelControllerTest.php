<?php

namespace Tests\Feature\Http\Controllers;

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
    public function destroy_returns_an_ok_response()
    {

        $channel = \App\Models\Channel::factory()->create();

        $response = $this->delete(route('channels.destroy', [$channel]));

        $response->assertOk();
        $this->assertDeleted($channel);
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {

        $response = $this->get(route('channels.index'));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {

        $channel = \App\Models\Channel::factory()->create();

        $response = $this->get(route('channels.show', [$channel]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $response = $this->post(route('channels.store'), [
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $channel = \App\Models\Channel::factory()->create();

        $response = $this->put(route('channels.update', ['channel' => $channel->id]), [
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail,
            'description' => 'Test Update',
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
