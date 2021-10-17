<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Monitor;
use App\Models\MonitorEvent;
use App\Models\MonitorUptimeEventCount;
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
        MonitorUptimeEventCount::factory()->count(10)->create(['user_id' => $this->testUser->id]);

        $response = $this->get(route('event-counts-grouped.index'));

        $response->assertOk();
        $this->assertResponseJson($response, 'event-count.json');
    }

    /**
     * @test
     * @covers ::show
     */
    public function it_shows_event_counts_for_a_monitor()
    {
        $monitor = Monitor::factory()
            ->hasUptimeEventCounts(10, ['user_id' => $this->testUser->id])
            ->create(['user_id' => $this->testUser->id]);

        $response = $this->get(route('event-counts-grouped.show', $monitor));

        $response->assertOk();
        $this->assertResponseJson($response, 'event-count.json');
    }
}
