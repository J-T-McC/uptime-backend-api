<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StoreMonitorRequest;
use App\Models\Monitor;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\AuthenticatedTestCase;

#[CoversClass(StoreMonitorRequest::class)]
class StoreMonitorRequestTest extends AuthenticatedTestCase
{
    /**
     * @see StoreMonitorRequest::rules
     */
    public function test_it_validates_store_channels_request()
    {
        $route = route('monitors.store');

        // required
        $this->assertRequestRules($route, ['url' => null], 'url');
        // string
        $this->assertRequestRules($route, ['url' => PHP_INT_MAX], 'url');
        // value restricted
        $this->assertRequestRules($route, ['url' => 'invalid-url'], 'url');
        // unique for user
        $duplicateMonitor = Monitor::factory()->createQuietly(['user_id' => $this->testUser->id]);
        $this->assertRequestRules($route, $duplicateMonitor->toArray(), 'url');
        // boolean
        $this->assertRequestRules($route, ['certificate_check_enabled' => PHP_INT_MAX], 'certificate_check_enabled');
        $this->assertRequestRules($route, ['certificate_check_enabled' => 'string'], 'certificate_check_enabled');
        // string
        $this->assertRequestRules($route, ['look_for_string' => PHP_INT_MAX], 'look_for_string');
        // boolean
        $this->assertRequestRules($route, ['uptime_check_enabled' => PHP_INT_MAX], 'uptime_check_enabled');
        $this->assertRequestRules($route, ['uptime_check_enabled' => 'string'], 'uptime_check_enabled');
    }
}
