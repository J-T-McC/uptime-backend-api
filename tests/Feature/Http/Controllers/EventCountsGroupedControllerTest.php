<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthenticatedTestCase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\EventCountsGroupedController
 */
class EventCountsGroupedControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {

        $response = $this->get(route('event-counts-grouped.index'));

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
        $response = $this->get(route('event-counts-grouped.show', ['event_counts_grouped' => $event->monitor_id]));
        $response->assertOk();
    }

    // test cases...
}
