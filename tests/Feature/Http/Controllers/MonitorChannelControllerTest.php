<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Channel;
use App\Models\Monitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    public function it_associates_channels_with_monitors()
    {
        $monitor = Monitor::factory()->create();

        $body = [
            "id[" . Channel::factory()->create()->id . "]" => true,
            "id[" . Channel::factory()->create()->id . "]" => true,
            "id[" . Channel::factory()->create()->id . "]" => true,
        ];

        $response = $this->put(route('monitors-channels.update', $monitor), $body);

        $response->assertOk();
    }
}
