<?php

namespace Tests\Feature\Http\Requests;

use App\Models\Channel;
use Tests\AuthenticatedTestCase;

class StoreChannelRequestTest extends AuthenticatedTestCase
{
    /**
     * @test
     *
     * @covers \App\Http\Requests\StoreChannelRequest::rules
     */
    public function it_validates_store_channels_request()
    {
        $route = route('channels.store');

        // required
        $this->assertRequestRules($route, ['type' => null], 'type');
        // string
        $this->assertRequestRules($route, ['type' => PHP_INT_MAX], 'type');
        // value restricted
        $this->assertRequestRules($route, ['type' => 'invalid-type'], 'type');
        // unique for user
        $duplicateChannel = Channel::factory()->createQuietly(['user_id' => $this->testUser->id]);
        $this->assertRequestRules($route, $duplicateChannel->toArray(), 'type');
        foreach (array_keys(config('uptime-monitor.notifications.service-endpoint-rules')) as $type) {
            $this->assertRequestRules($route, ['type' => $type, 'endpoint' => 'invalid-endpoint'], 'endpoint');
        }
    }
}
