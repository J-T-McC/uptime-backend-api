<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Monitor;
use Tests\AuthenticatedTestCase;

/**
 * @coversDefaultClass  \App\Http\Controllers\LatestMonitorEventsController
 */
class LatestMonitorEventsControllerTest extends AuthenticatedTestCase
{
    /**
     * @test
     *
     * @covers ::index
     */
    public function it_lists_events()
    {
        Monitor::factory()->hasMonitorEvents(10)->createQuietly(['user_id' => $this->testUser->id]);

        $response = $this->getJson(route('latest-monitor-events.index'));

        $response->assertOk();
        $this->assertResponseCollectionJson($response, 'event.json');
    }

    /**
     * @test
     *
     * @covers ::show
     */
    public function it_lists_events_for_a_monitor()
    {
        $monitor = Monitor::factory()->hasMonitorEvents(10)->createQuietly(['user_id' => $this->testUser->id]);

        $response = $this->getJson(route('latest-monitor-events.show', $monitor));

        $response->assertOk();
        $this->assertResponseCollectionJson($response, 'event.json');
    }
}
