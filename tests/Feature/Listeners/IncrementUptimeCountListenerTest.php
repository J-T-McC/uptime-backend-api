<?php

namespace Tests\Feature\Listeners;

use App\Models\Monitor;
use App\Models\MonitorUptimeEventCount;
use Tests\TestCase;

/**
 * @see \App\Listeners\IncrementUptimeCountListener
 */
class IncrementUptimeCountListenerTest extends TestCase
{
    /**
     * @test
     */
    public function increments_uptime_status_failed()
    {
        $monitor = Monitor::factory()->create();
        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $monitor->uptimeCheckFailed('(┛ಠ_ಠ)┛彡┻━┻');

        $this->assertSame(1, MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->down);

        $monitor->uptimeCheckFailed('(┛ಠ_ಠ)┛彡┻━┻');

        $this->assertSame(2, MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->down);
    }

    /**
     * @test
     */
    public function increments_uptime_status_up()
    {
        $monitor = Monitor::factory()->create();

        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $monitor->uptimeCheckSucceeded();

        $this->assertSame(1, MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->up);

        $monitor->uptimeCheckSucceeded();

        $this->assertSame(2, MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->up);
    }

    /**
     * @test
     */
    public function increments_uptime_status_recovered()
    {
        $monitor = Monitor::factory()->create();

        // no count record for this monitor
        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $monitor->uptimeCheckFailed('(┛ಠ_ಠ)┛彡┻━┻');
        $monitor->uptimeCheckSucceeded('┬─┬ノ( º _ ºノ)');

        $this->assertSame(1, MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->recovered);

        $monitor->uptimeCheckFailed('(┛ಠ_ಠ)┛彡┻━┻');
        $monitor->uptimeCheckSucceeded('┬─┬ノ( º _ ºノ)');

        $this->assertSame(2, MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->recovered);
    }
}
