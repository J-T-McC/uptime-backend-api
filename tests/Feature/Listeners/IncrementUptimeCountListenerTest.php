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

        $monitor->uptimeRequestFailed('(┛ಠ_ಠ)┛彡┻━┻');

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->down === 1);

        $monitor->uptimeRequestFailed('(┛ಠ_ಠ)┛彡┻━┻');

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->down === 2);

    }

    /**
     * @test
     */
    public function increments_uptime_status_up()
    {
        $monitor = Monitor::factory()->create();

        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $monitor->uptimeCheckSucceeded();

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->up === 1);

        $monitor->uptimeCheckSucceeded();

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->up === 2);
    }

    /**
     * @test
     */
    public function increments_uptime_status_recovered()
    {
        $monitor = Monitor::factory()->create();

        // no count record for this monitor
        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $monitor->uptimeRequestFailed('(┛ಠ_ಠ)┛彡┻━┻');
        $monitor->uptimeCheckSucceeded('┬─┬ノ( º _ ºノ)');

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->recovered === 1);

        $monitor->uptimeRequestFailed('(┛ಠ_ಠ)┛彡┻━┻');
        $monitor->uptimeCheckSucceeded('┬─┬ノ( º _ ºノ)');

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->recovered === 2);
    }
}
