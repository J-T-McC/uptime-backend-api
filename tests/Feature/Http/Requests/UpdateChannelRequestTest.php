<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\AuthenticatedTestCase;

#[CoversClass(UpdateChannelRequest::class)]
class UpdateChannelRequestTest extends AuthenticatedTestCase
{
    /**
     * @see UpdateChannelRequest::rules
     */
    public function test_it_validates_update_channels_request()
    {
        $channels = Channel::factory()->count(2)->createQuietly(['user_id' => $this->testUser->id]);
        $route = route('channels.update', $channels->first());

        // required
        $this->assertRequestRules($route, ['type' => null], 'type', 'putJson');
        // string
        $this->assertRequestRules($route, ['type' => PHP_INT_MAX], 'type', 'putJson');
        // value restricted
        $this->assertRequestRules($route, ['type' => 'invalid-type'], 'type', 'putJson');
        // unique for user
        $this->assertRequestRules($route, ['endpoint' => $channels->last()->endpoint, 'type' => $channels->last()->type], 'type', 'putJson');
        foreach (array_keys(config('uptime-monitor.notifications.service-endpoint-rules')) as $type) {
            $this->assertRequestRules($route, ['type' => $type, 'endpoint' => 'invalid-endpoint'], 'endpoint', 'putJson');
        }
    }
}
