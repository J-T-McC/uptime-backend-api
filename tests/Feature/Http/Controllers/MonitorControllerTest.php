<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\MonitorController;
use App\Models\Channel;
use App\Models\Monitor;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\AuthenticatedTestCase;

#[CoversClass(MonitorController::class)]
class MonitorControllerTest extends AuthenticatedTestCase
{
    /**
     * @see MonitorController::destroy
     */
    public function test_it_deletes_monitors()
    {
        $monitor = Monitor::factory()->createQuietly(['user_id' => $this->testUser->id]);

        $response = $this->deleteJson(route('monitors.destroy', $monitor));

        $response->assertNoContent();
        $this->assertModelMissing($monitor);
    }

    /**
     * @see MonitorController::index
     */
    public function test_it_lists_monitors()
    {
        Monitor::factory()
            ->count(10)
            ->createQuietly(['user_id' => $this->testUser->id])
            ->each(fn (Monitor $monitor) => $monitor->channels()->sync(
                Channel::factory()->createQuietly(['user_id' => $this->testUser->id])
            ));

        $response = $this->getJson(route('monitors.index'));

        $response->assertOk();
        $this->assertResponseCollectionJson($response, 'monitor.json');
    }

    /**
     * @see MonitorController::show
     */
    public function test_it_shows_monitors()
    {
        $monitor = Monitor::factory()->createQuietly();

        $response = $this->getJson(route('monitors.show', $monitor));

        $response->assertOk();
        $this->assertResponseJson($response, 'monitor.json');
    }

    /**
     * @see MonitorController::store
     */
    public function test_it_stores_monitors()
    {
        $response = $this->postJson(route('monitors.store'), [
            'url' => 'http://example.com',
        ]);

        $response->assertCreated();
    }

    /**
     * @see MonitorController::update
     */
    public function test_it_updates_monitors()
    {
        $monitor = Monitor::factory()->createQuietly();

        $response = $this->putJson(route('monitors.update', $monitor), [
            'url' => 'http://example.com',
        ]);

        $response->assertOk();
    }
}
