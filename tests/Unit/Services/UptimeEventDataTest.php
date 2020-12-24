<?php

namespace Tests\Unit\Services;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\UptimeEventData;
use App\Models\MonitorUptimeEventCount;

use Tests\Fixtures\Models\Car;
use Tests\TestCase;

/**
 * @see UptimeEventData
 */
class UptimeEventDataTest extends TestCase
{

    use RefreshDatabase;


    /**
     * @test
     */
    public function generates_correct_aggregate_trended_weekly()
    {
        $this->seedTrendData();
        $uptimeEventData = new UptimeEventData();
        $trended = $uptimeEventData->trendedMonthly();

        //expected results for 2020 ISO 8601 first two weeks based on test data
        $expectedResults = [
            'Dec 30 - Jan 05' => "73.6285",
            'Jan 06 - Jan 12' => "58.6632",
        ];

        $trended->each(fn($series) => $this->assertTrue($series->percent === $expectedResults[$series->category]));
    }


    /**
     * @test
     */
    public function seedGroupedData()
    {
        $this->refreshDatabase();

        $user = \App\Models\User::factory()->create();
        $monitor = \App\Models\Monitor::factory()->create();

        for ($i = 0; $i < 100; $i++) {
            $date = Carbon::now('UTC')->subDays($i)->format('Y-m-d');
            MonitorUptimeEventCount::factory()->create([
                'monitor_id' => $monitor->id,
                'user_id' => $user->id,
                'filter_date' => $date,
                'up' => 1438,
                'down' => 1,
                'recovered' => 1,
            ]);
        }

        $uptimeEventData = new UptimeEventData();
        $results =  $uptimeEventData->past90Days();

        $expectedResults = [
            'Down' => "0.0694",
            'Up' => "99.9306",
        ];

        $results->each(fn($series) => $this->assertTrue($series->percent === $expectedResults[$series->category]));
    }


    public function seedTrendData()
    {

        $seedData = [
            //73.6285
            '2020-01-01' => [
                'up' => 1400,
                'down' => 39,
                'recovered' => 1,
            ],
            '2020-01-02' => [
                'up' => 1000,
                'down' => 440,
                'recovered' => 0,
            ],
            '2020-01-03' => [
                'up' => 350,
                'down' => 1040,
                'recovered' => 50,
            ],
            '2020-01-05' => [
                'up' => 1440,
                'down' => 0,
                'recovered' => 0,
            ],

            //58.6632
            '2020-01-06' => [
                'up' => 1438,
                'down' => 1,
                'recovered' => 1,
            ],
            '2020-01-07' => [
                'up' => 0,
                'down' => 1440,
                'recovered' => 0,
            ],
            '2020-01-08' => [
                'up' => 500,
                'down' => 500,
                'recovered' => 440,
            ],
            '2020-01-09' => [
                'up' => 440,
                'down' => 440,
                'recovered' => 560,
            ],
        ];

        $user = \App\Models\User::factory()->create();
        $monitor = \App\Models\Monitor::factory()->create();

        foreach ($seedData as $date => $counts) {
            MonitorUptimeEventCount::factory()->create(array_merge(
                [
                    'monitor_id' => $monitor->id,
                    'user_id' => $user->id,
                    'filter_date' => $date
                ], $counts
            ));
        }
    }


}
