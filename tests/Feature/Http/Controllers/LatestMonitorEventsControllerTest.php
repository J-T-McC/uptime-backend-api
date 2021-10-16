<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MonitorEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedTestCase;

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
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $event = MonitorEvent::factory()->create();

        $response = $this->get(route('latest-monitor-events.show', $event->monitor));

        $response->assertOk();
    }
}
