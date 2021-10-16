<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MonitorEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        MonitorEvent::factory([
            'user_id' => $this->testUser->id
        ])->count(10)->create();

        $response = $this->get(route('event-counts-trended.index'));

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
        $response = $this->get(route('event-counts-trended.show', $event->monitor));
        $response->assertOk();
    }
}
