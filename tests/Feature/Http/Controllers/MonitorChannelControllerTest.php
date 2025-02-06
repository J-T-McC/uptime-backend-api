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
     *
     * @covers ::update
     */
    public function it_associates_channels_with_monitors()
    {
        // Collect
        $monitor = Monitor::factory()->for($this->testUser)->createQuietly();

        $body = [
            Channel::factory()->for($this->testUser)->createQuietly()->hashId => true,
            Channel::factory()->for($this->testUser)->createQuietly()->hashId => true,
            Channel::factory()->for($this->testUser)->createQuietly()->hashId => true,
            Channel::factory()->for($this->testUser)->createQuietly()->hashId => false,
            Channel::factory()->createQuietly()->hashId => false,
        ];

        // Act
        $response = $this->putJson(route('monitors-channels.update', $monitor), $body);

        // Assert
        $response->assertNoContent();
        $this->assertCount(3, $monitor->channels);
    }
}
