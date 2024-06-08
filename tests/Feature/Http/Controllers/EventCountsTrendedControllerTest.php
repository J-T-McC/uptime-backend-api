<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Monitor;
use Tests\AuthenticatedTestCase;

/**
 * @coversDefaultClass  \App\Http\Controllers\EventCountsTrendedController
 */
class EventCountsTrendedControllerTest extends AuthenticatedTestCase
{
    /**
     * @test
     * @covers ::index
     */
    public function it_lists_trended_event_counts()
    {
        Monitor::factory()
            ->count(2)
            ->hasUptimeEventCounts(10, ['user_id' => $this->testUser->id])
            ->create(['user_id' => $this->testUser->id]);

        $response = $this->getJson(route('event-counts-trended.index'));

        $response->assertOk();
        $this->assertResponseJson($response, 'trended-event-count.json');
    }

    /**
     * @test
     * @covers ::show
     */
    public function it_lists_trended_event_counts_for_a_monitor()
    {
        $monitor = Monitor::factory()
            ->hasUptimeEventCounts(10, ['user_id' => $this->testUser->id])
            ->create(['user_id' => $this->testUser->id]);

        $response = $this->getJson(route('event-counts-trended.show', $monitor));

        $this->assertResponseJson($response, 'trended-event-count.json');
        $response->assertOk();
    }
}
