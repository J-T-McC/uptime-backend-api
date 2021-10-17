<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MonitorEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedTestCase;

/**
 * @coversDefaultClass  \App\Http\Controllers\LatestMonitorEventsController
 */
class LatestMonitorEventsControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers ::index
     */
    public function it_lists_events()
    {
        $response = $this->get(route('latest-monitor-events.index'));

        $response->assertOk();
    }

    /**
     * @test
     * @covers ::show
     */
    public function it_lists_events_for_a_monitor()
    {
        $event = MonitorEvent::factory()->create();

        $response = $this->get(route('latest-monitor-events.show', $event->monitor));

        $response->assertOk();
    }
}
