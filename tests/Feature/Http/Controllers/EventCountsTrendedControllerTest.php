<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MonitorEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedTestCase;

/**
 * @coversDefaultClass  \App\Http\Controllers\EventCountsTrendedController
 */
class EventCountsTrendedControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers ::index
     */
    public function it_lists_trended_event_counts()
    {
        MonitorEvent::factory([
            'user_id' => $this->testUser->id
        ])->count(10)->create();

        $response = $this->get(route('event-counts-trended.index'));

        $response->assertOk();
    }

    /**
     * @test
     * @covers ::show
     */
    public function it_lists_trended_event_counts_for_a_monitor()
    {
        $event = MonitorEvent::factory([
            'user_id' => $this->testUser->id
        ])->create();

        $response = $this->get(route('event-counts-trended.show', $event->monitor));

        $response->assertOk();
    }
}
