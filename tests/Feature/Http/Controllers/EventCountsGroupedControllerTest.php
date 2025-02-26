<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\EventCountsGroupedController;
use App\Models\Monitor;
use App\Models\MonitorUptimeEventCount;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\AuthenticatedTestCase;

#[CoversClass(EventCountsGroupedController::class)]
class EventCountsGroupedControllerTest extends AuthenticatedTestCase
{
    /**
     * @see EventCountsGroupedController::index
     */
    public function test_it_lists_event_counts()
    {
        MonitorUptimeEventCount::factory()->count(10)->createQuietly(
            ['user_id' => $this->testUser->id, 'filter_date' => now()->subDays(10)->toDateString()]
        );

        $response = $this->getJson(route('event-counts-grouped.index'));

        $response->assertOk();
        $this->assertResponseJson($response, 'event-count.json');
    }

    /**
     * @see EventCountsGroupedController::show
     */
    public function test_it_shows_event_counts_for_a_monitor()
    {
        $monitor = Monitor::factory()
            ->hasUptimeEventCounts(
                10,
                ['user_id' => $this->testUser->id, 'filter_date' => now()->subDays(10)->toDateString()]
            )
            ->createQuietly([
                'user_id' => $this->testUser->id,
            ]);

        $response = $this->getJson(route('event-counts-grouped.show', $monitor));

        $response->assertOk();
        $this->assertResponseJson($response, 'event-count.json');
    }
}
