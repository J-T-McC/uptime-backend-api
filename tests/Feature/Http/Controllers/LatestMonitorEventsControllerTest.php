<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthenticatedTestCase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\LatestMonitorEventsController
 */
class LatestMonitorEventsControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {

        $response = $this->get(route('latest-monitor-events.index'));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {

        $event = \App\Models\MonitorEvent::factory()->create();

        $response = $this->get(route('latest-monitor-events.show', ['latest_monitor_event' => $event->monitor_id]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
