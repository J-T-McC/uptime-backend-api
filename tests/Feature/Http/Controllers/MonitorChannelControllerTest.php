<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthenticatedTestCase;

/**
 * @see \App\Http\Controllers\MonitorChannelController
 */
class MonitorChannelControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $monitor = \App\Models\Monitor::factory()->create();

        $body = [
            "id[" . \App\Models\Channel::factory()->create()->id . "]" => true,
            "id[" . \App\Models\Channel::factory()->create()->id . "]" => true,
            "id[" . \App\Models\Channel::factory()->create()->id . "]" => true,
        ];

        $response = $this->put(route('monitors-channels.update', ['monitors_channel' => $monitor->id]), $body);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
