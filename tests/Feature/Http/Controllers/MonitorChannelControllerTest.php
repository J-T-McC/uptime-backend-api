<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\MonitorChannelController;
use App\Models\Channel;
use App\Models\Monitor;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\AuthenticatedTestCase;

#[CoversClass(MonitorChannelController::class)]
class MonitorChannelControllerTest extends AuthenticatedTestCase
{
    /**
     * @see MonitorChannelController::update
     */
    public function test_it_associates_channels_with_monitors()
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
