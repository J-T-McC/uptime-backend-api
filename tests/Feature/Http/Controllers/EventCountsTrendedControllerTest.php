<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthenticatedTestCase;

/**
 * @see \App\Http\Controllers\EventCountsTrendedController
 */
class EventCountsTrendedControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {

        \App\Models\MonitorEvent::factory([
            'user_id' => $this->testUser->id
        ])->count(10)->create();

        $response = $this->get(route('event-counts-trended.index'));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $event = \App\Models\MonitorEvent::factory([
            'user_id' => $this->testUser->id
        ])->create();
        $response = $this->get(route('event-counts-trended.show', ['event_counts_trended' => $event->monitor_id]));
        $response->assertOk();
    }

    // test cases...
}
