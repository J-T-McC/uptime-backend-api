<?php

namespace Tests\Feature\Http\Requests;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthenticatedTestCase;

/**
 * @see \App\Http\Requests\ChannelRequest
 */
class ChannelRequestTest extends AuthenticatedTestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function store_requires_unique_type_and_endpoint()
    {

        $channel = Channel::factory([
            'user_id' => $this->testUser->id,
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail,
        ])->create();

        $response = $this->post(route('channels.store'), [
            'type' => $channel->type,
            'endpoint' => $channel->endpoint
        ], ['Accept' => "application/json"]);

        $response->assertStatus(422);

        //make channel to insert
        $secondChannel = Channel::factory([
            'user_id' => $this->testUser->id,
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail,
        ])->make();

        $secondResponse = $this->post(route('channels.store'), [
            'type' => $secondChannel->type,
            'endpoint' => $secondChannel->endpoint
        ], ['Accept' => "application/json"]);

        $secondResponse->assertCreated();
    }

    /**
     * @test
     */
    public function update_requires_unique_type_and_endpoint()
    {

        $conflictingChannel = Channel::factory([
            'user_id' => $this->testUser->id,
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail,
        ])->create();

        $updateChannel = Channel::factory([
            'user_id' => $this->testUser->id,
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail,
        ])->create();

        $response = $this->put(route('channels.update', ['channel' => $updateChannel->id]), [
            'type' => $conflictingChannel->type,
            'endpoint' => $conflictingChannel->endpoint,
        ], ['Accept' => "application/json"]);

        $response->assertStatus(422);

        $response = $this->put(route('channels.update', ['channel' => $updateChannel->id]), [
            'type' => $updateChannel->type,
            'endpoint' => $updateChannel->endpoint,
        ], ['Accept' => "application/json"]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function slack_endpoint_requires_url()
    {
        $response = $this->post(route('channels.store'), [
            'type' => 'slack',
            'endpoint' => 'not a url'
        ], ['Accept' => "application/json"]);

        $response->assertStatus(422);

        $secondResponse = $this->post(route('channels.store'), [
            'type' => 'slack',
            'endpoint' => 'http://example.com'
        ], ['Accept' => "application/json"]);

        $secondResponse->assertCreated();
    }

    /**
     * @test
     */
    public function discord_endpoint_requires_url()
    {
        $response = $this->post(route('channels.store'), [
            'type' => 'discord',
            'endpoint' => 'not a url'
        ], ['Accept' => "application/json"]);

        $response->assertStatus(422);

        $secondResponse = $this->post(route('channels.store'), [
            'type' => 'discord',
            'endpoint' => 'http://example.com'
        ], ['Accept' => "application/json"]);

        $secondResponse->assertCreated();
    }

    /**
     * @test
     */
    public function mail_endpoint_requires_email()
    {
        $response = $this->post(route('channels.store'), [
            'type' => 'mail',
            'endpoint' => 'not an email'
        ], ['Accept' => "application/json"]);

        $response->assertStatus(422);

        $secondResponse = $this->post(route('channels.store'), [
            'type' => 'mail',
            'endpoint' => $this->faker->safeEmail
        ], ['Accept' => "application/json"]);

        $secondResponse->assertCreated();
    }
}
