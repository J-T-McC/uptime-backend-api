<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MonitorEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedTestCase;

/**
 * @coversDefaultClass  \App\Http\Controllers\EventCountsGroupedController
 */
class EventCountsGroupedControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers ::index
     */
    public function it_lists_event_counts()
    {
        $response = $this->get(route('event-counts-grouped.index'));

        $response->assertOk();
    }

    /**
     * @test
     * @covers ::show
     */
    public function sit_shows_event_counts_for_a_monitor()
    {
        $event = MonitorEvent::factory([
            'user_id' => $this->testUser->id
        ])->create();

        $response = $this->get(route('event-counts-grouped.show', $event->monitor));

        $response->assertOk();
    }
}
