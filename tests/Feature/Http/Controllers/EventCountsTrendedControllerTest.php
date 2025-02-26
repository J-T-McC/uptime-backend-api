<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\EventCountsTrendedController;
use App\Models\Monitor;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\AuthenticatedTestCase;

#[CoversClass(EventCountsTrendedController::class)]
class EventCountsTrendedControllerTest extends AuthenticatedTestCase
{
    /**
     * @see EventCountsTrendedController::index
     */
    public function test_it_lists_trended_event_counts()
    {
        Monitor::factory()
            ->count(2)
            ->hasUptimeEventCounts(10, ['user_id' => $this->testUser->id])
            ->createQuietly(['user_id' => $this->testUser->id]);

        $response = $this->getJson(route('event-counts-trended.index'));

        $response->assertOk();
        $this->assertResponseJson($response, 'trended-event-count.json');
    }

    /**
     * @see EventCountsTrendedController::show
     */
    public function test_it_lists_trended_event_counts_for_a_monitor()
    {
        $monitor = Monitor::factory()
            ->hasUptimeEventCounts(10, ['user_id' => $this->testUser->id])
            ->createQuietly(['user_id' => $this->testUser->id]);

        $response = $this->getJson(route('event-counts-trended.show', $monitor));

        $this->assertResponseJson($response, 'trended-event-count.json');
        $response->assertOk();
    }
}
