<?php

namespace Tests\Feature\Listeners;

use App\Listeners\IncrementUptimeCountListener;
use App\Models\Monitor;
use App\Models\MonitorUptimeEventCount;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(IncrementUptimeCountListener::class)]
class IncrementUptimeCountListenerTest extends TestCase
{
    /**
     * @see IncrementUptimeCountListener::handle
     */
    public function test_it_increments_uptime_status_failed()
    {
        $monitor = Monitor::factory()->createQuietly();
        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $monitor->uptimeCheckFailed('(┛ಠ_ಠ)┛彡┻━┻');

        $this->assertSame(1, MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->down);

        $monitor->uptimeCheckFailed('(┛ಠ_ಠ)┛彡┻━┻');

        $this->assertSame(2, MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->down);
    }

    /**
     * @see IncrementUptimeCountListener::handle
     */
    public function test_it_increments_uptime_status_up()
    {
        $monitor = Monitor::factory()->createQuietly();

        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $monitor->uptimeCheckSucceeded();

        $this->assertSame(1, MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->up);

        $monitor->uptimeCheckSucceeded();

        $this->assertSame(2, MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->up);
    }

    /**
     * @see IncrementUptimeCountListener::handle
     */
    public function test_increments_uptime_status_recovered()
    {
        $monitor = Monitor::factory()->createQuietly();

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
