<?php

namespace Tests\Feature\Http\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthenticatedTestCase;

/**
 * @see \App\Http\Requests\MonitorRequest
 */
class MonitorRequestTest extends AuthenticatedTestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function store_requires_unique_url_and_user() {

        $channel = \App\Models\Monitor::factory([
            'user_id' => $this->testUser->id,
            'url' => 'http://example.com',
        ])->create();

        $response = $this->post(route('monitors.store'), [
            'user_id' => $channel->user_id,
            'url' => $channel->raw_url,
        ], ['Accept' => "application/json"]);

        $response->assertStatus(422);

        $secondResponse = $this->post(route('monitors.store'), [
            'user_id' => $this->testUser->id,
            'url' => 'http://google.com',
        ], ['Accept' => "application/json"]);

        $secondResponse->assertOk();

    }

    /**
     * @test
     */
    public function update_requires_unique_type_and_endpoint() {

        $conflictingMonitor = \App\Models\Monitor::factory([
            'user_id' => $this->testUser->id,
            'url' => 'http://example.com',
        ])->create();

        $updateMonitor = \App\Models\Monitor::factory([
            'user_id' => $this->testUser->id,
            'url' => 'http://google.com',
        ])->create();

        $response = $this->put(route('monitors.update', ['monitor' => $updateMonitor->id]), [
            'user_id' => $conflictingMonitor->user_id,
            'url' => $conflictingMonitor->raw_url,
        ], ['Accept' => "application/json"]);

        $response->assertStatus(422);

        $response = $this->put(route('monitors.update', ['monitor' => $updateMonitor->id]), [
            'user_id' => $updateMonitor->user_id,
            'url' => $updateMonitor->raw_url,
        ], ['Accept' => "application/json"]);

        $response->assertOk();
    }

}
