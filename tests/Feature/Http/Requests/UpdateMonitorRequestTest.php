<?php

namespace Tests\Feature\Http\Requests;

use App\Models\Monitor;
use Tests\AuthenticatedTestCase;

class UpdateMonitorRequestTest extends AuthenticatedTestCase
{
    /**
     * @test
     *
     * @covers \App\Http\Requests\StoreMonitorRequest::rules
     */
    public function it_validates_store_channels_request()
    {
        $monitors = Monitor::factory()->count(2)->createQuietly(['user_id' => $this->testUser->id]);
        $route = route('monitors.update', $monitors->first());

        // required
        $this->assertRequestRules($route, ['url' => null], 'url', 'putJson');
        // string
        $this->assertRequestRules($route, ['url' => PHP_INT_MAX], 'url', 'putJson');
        // value restricted
        $this->assertRequestRules($route, ['url' => 'invalid-url'], 'url', 'putJson');
        // unique for user
        $this->assertRequestRules($route, ['url' => $monitors->last()->url], 'url', 'putJson');
        // boolean
        $this->assertRequestRules($route, ['certificate_check_enabled' => PHP_INT_MAX], 'certificate_check_enabled', 'putJson');
        $this->assertRequestRules($route, ['certificate_check_enabled' => 'string'], 'certificate_check_enabled', 'putJson');
        // string
        $this->assertRequestRules($route, ['look_for_string' => PHP_INT_MAX], 'look_for_string', 'putJson');
        // boolean
        $this->assertRequestRules($route, ['uptime_check_enabled' => PHP_INT_MAX], 'uptime_check_enabled', 'putJson');
        $this->assertRequestRules($route, ['uptime_check_enabled' => 'string'], 'uptime_check_enabled', 'putJson');
    }
}
