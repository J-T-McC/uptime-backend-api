<?php

namespace Tests\Integration\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\UptimeMonitor\Helpers\ConsoleOutput;
use Spatie\UptimeMonitor\MonitorCollection;
use App\Models\MonitorUptimeEventCount;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

/**
 * @see \App\Listeners\IncrementUptimeCountListener
 */
class IncrementUptimeCountListenerTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        //initializes ConsoleOutput singleton allowing us to directly call checkUptime on a monitor later
        Artisan::call('monitor:check-uptime');
    }

    /**
     * @test
     */
    public function increments_uptime_status_failed()
    {
        $monitor = \App\Models\Monitor::factory(['url' => static::UPTIME_FAIL])->create();
        $collection = MonitorCollection::make([$monitor]);

        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $collection->checkUptime();

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->down === 1);

        $collection->checkUptime();

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->down === 2);

    }

    /**
     * @test
     */
    public function increments_uptime_status_up()
    {
        $monitor = \App\Models\Monitor::factory()->create();
        $monitor->url = static::UPTIME_SUCCEED;
        $collection = MonitorCollection::make([$monitor]);

        $this->assertTrue(empty(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()));

        $collection->checkUptime();

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->up === 1);

        $collection->checkUptime();

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

        $monitor->url = static::UPTIME_FAIL;
        $collection = MonitorCollection::make([$monitor]);
        $collection->checkUptime();

        $monitor->url = static::UPTIME_SUCCEED;
        $collection = MonitorCollection::make([$monitor]);
        $collection->checkUptime();

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->recovered === 1);

        $monitor->url = static::UPTIME_FAIL;
        $collection = MonitorCollection::make([$monitor]);
        $collection->checkUptime();

        $monitor->url = static::UPTIME_SUCCEED;
        $collection = MonitorCollection::make([$monitor]);
        $collection->checkUptime();

        $this->assertTrue(MonitorUptimeEventCount::where('monitor_id', $monitor->id)->first()->recovered === 2);
    }

}
