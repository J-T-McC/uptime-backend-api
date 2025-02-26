<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\LatestMonitorEventsController;
use App\Models\Monitor;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\AuthenticatedTestCase;

#[CoversClass(LatestMonitorEventsController::class)]
class LatestMonitorEventsControllerTest extends AuthenticatedTestCase
{
    /**
     * @see LatestMonitorEventsController::index
     */
    public function test_it_lists_events()
    {
        Monitor::factory()->hasMonitorEvents(10)->createQuietly(['user_id' => $this->testUser->id]);

        $response = $this->getJson(route('latest-monitor-events.index'));

        $response->assertOk();
        $this->assertResponseCollectionJson($response, 'event.json');
    }

    /**
     * @see LatestMonitorEventsController::show
     */
    public function test_it_lists_events_for_a_monitor()
    {
        $monitor = Monitor::factory()->hasMonitorEvents(10)->createQuietly(['user_id' => $this->testUser->id]);

        $response = $this->getJson(route('latest-monitor-events.show', $monitor));

        $response->assertOk();
        $this->assertResponseCollectionJson($response, 'event.json');
    }
}
