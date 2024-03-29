<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Channel;
use App\Models\Monitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedTestCase;

/**
 * @coversDefaultClass  \App\Http\Controllers\MonitorController
 */
class MonitorControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers ::destroy
     */
    public function it_deletes_monitors()
    {
        $monitor = Monitor::factory()->create(['user_id' => $this->testUser->id]);

        $response = $this->deleteJson(route('monitors.destroy', $monitor));

        $response->assertNoContent();
        $this->assertDeleted($monitor);
    }

    /**
     * @test
     * @covers ::index
     */
    public function it_lists_monitors()
    {
        Monitor::factory()
            ->count(10)
            ->create(['user_id' => $this->testUser->id])
            ->each(fn(Monitor $monitor) => $monitor->channels()->sync(
                Channel::factory()->create(['user_id' => $this->testUser->id])
            ));

        $response = $this->getJson(route('monitors.index'));

        $response->assertOk();
        $this->assertResponseCollectionJson($response, 'monitor.json');
    }

    /**
     * @test
     * @covers ::show
     */
    public function it_shows_monitors()
    {
        $monitor = Monitor::factory()->create();

        $response = $this->getJson(route('monitors.show', $monitor));

        $response->assertOk();
        $this->assertResponseJson($response, 'monitor.json');
    }

    /**
     * @test
     * @covers ::store
     */
    public function it_stores_monitors()
    {
        $response = $this->postJson(route('monitors.store'), [
            'url' => 'http://example.com'
        ]);

        $response->assertCreated();
    }

    /**
     * @test
     * @covers ::update
     */
    public function it_updates_monitors()
    {
        $monitor = Monitor::factory()->create();

        $response = $this->putJson(route('monitors.update', $monitor), [
            'url' => 'http://example.com'
        ]);

        $response->assertOk();
    }
}
