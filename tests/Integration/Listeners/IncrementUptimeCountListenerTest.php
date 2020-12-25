<?php

namespace Tests\Integration\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\UptimeMonitor\MonitorCollection;
use App\Models\MonitorUptimeEventCount;
use Tests\TestCase;


/**
 * @see \App\Listeners\IncrementUptimeCountListener
 */
class IncrementUptimeCountListenerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function increments_uptime_status_failed()
    {
        $monitor = \App\Models\Monitor::factory()->create();
        $monitor->url = static::uptimeFail;
        $collection = MonitorCollection::make([$monitor]);

        //no count record for this monitor
        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $collection->checkUptime();

        //record exists and incremented by 1
        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->down === 1);

        $collection->checkUptime();

        //incremented by 2
        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->down === 2);

    }

    /**
     * @test
     */
    public function increments_uptime_status_up()
    {
        $monitor = \App\Models\Monitor::factory()->create();
        $monitor->url = static::uptimeSucceed;
        $collection = MonitorCollection::make([$monitor]);

        //no count record for this monitor
        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $collection->checkUptime();

        //record exists and incremented by 1
        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->up === 1);

        $collection->checkUptime();

        //incremented by 2
        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->up === 2);
    }

    /**
     * @test
     */
    public function increments_uptime_status_recovered()
    {
        $monitor = \App\Models\Monitor::factory()->create();


        //no count record for this monitor
        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $monitor->url = static::uptimeFail;
        $collection = MonitorCollection::make([$monitor]);
        $collection->checkUptime();

        $monitor->url = static::uptimeSucceed;
        $collection = MonitorCollection::make([$monitor]);
        $collection->checkUptime();

        //record exists and incremented by 1
        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->recovered === 1);

        $monitor->url = static::uptimeFail;
        $collection = MonitorCollection::make([$monitor]);
        $collection->checkUptime();

        $monitor->url = static::uptimeSucceed;
        $collection = MonitorCollection::make([$monitor]);
        $collection->checkUptime();

        //incremented by 2
        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->recovered === 2);
    }

}
