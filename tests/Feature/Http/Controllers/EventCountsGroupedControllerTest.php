<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MonitorEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedTestCase;

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
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $event = MonitorEvent::factory([
            'user_id' => $this->testUser->id
        ])->create();
        $response = $this->get(route('event-counts-grouped.show', $event->monitor));
        $response->assertOk();
    }
}
