<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Channel;
use App\Models\Monitor;
use Tests\AuthenticatedTestCase;

/**
 * @coversDefaultClass  \App\Http\Controllers\MonitorChannelController
 */
class MonitorChannelControllerTest extends AuthenticatedTestCase
{
    /**
     * @test
     * @covers ::update
     */
    public function it_associates_channels_with_monitors()
    {
        $monitor = Monitor::factory()->createQuietly();

        $body = [
            "id[" . Channel::factory()->createQuietly()->id . "]" => true,
            "id[" . Channel::factory()->createQuietly()->id . "]" => true,
            "id[" . Channel::factory()->createQuietly()->id . "]" => true,
        ];

        $response = $this->putJson(route('monitors-channels.update', $monitor), $body);

        $response->assertNoContent();
    }
}
