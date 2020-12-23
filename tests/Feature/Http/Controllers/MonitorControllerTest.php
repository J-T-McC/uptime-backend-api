<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthenticatedTestCase;

/**
 * @see \App\Http\Controllers\MonitorController
 */
class MonitorControllerTest extends AuthenticatedTestCase
{
    use RefreshDatabase, WithFaker;


    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $monitor = \App\Models\Monitor::factory()->create();

        $response = $this->delete(route('monitors.destroy', ['monitor' => $monitor->id]));

        $response->assertOk();
        $this->assertDeleted($monitor);

    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $response = $this->get(route('monitors.index'));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $monitor = \App\Models\Monitor::factory()->create();

        $response = $this->get(route('monitors.show', [$monitor]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $response = $this->post(route('monitors.store'), [
            'url' => 'http://example.com'
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $monitor = \App\Models\Monitor::factory()->create();

        $response = $this->put(route('monitors.update', ['monitor' => $monitor->id]), [
           'url' => 'http://example.com'
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
