<?php

namespace Tests\Unit\Services;

use Carbon\Carbon;
use App\Services\UptimeEventData;
use App\Models\MonitorUptimeEventCount;
use Tests\TestCase;

/**
 * @see UptimeEventData
 */
class UptimeEventDataTest extends TestCase
{
    /**
     * @test
     */
    public function generates_correct_aggregate_trended()
    {
        $this->seedTrendData();
        $uptimeEventData = new UptimeEventData();
        $trended = $uptimeEventData->trended();

        $expectedResults = [
            'Jan 1st' => "97.2917",
            'Jan 2nd' => "69.4444",
            'Jan 3rd' => "27.7778",
            'Jan 5th' => "100.0000",
            'Jan 6th' => "99.9306",
            'Jan 7th' => "0.0000",
            'Jan 8th' => "65.2778",
            'Jan 9th' => "69.4444",
        ];

        $trended->each(fn($series) => $this->assertTrue($series->percent === $expectedResults[$series->category]));
    }

    /**
     * @test
     */
    public function generates_correct_aggregate_90days()
    {
        $this->seed90DayData();
        $uptimeEventData = new UptimeEventData();
        $results = $uptimeEventData->past90Days();

        $expectedResults = [
            'Down' => "0.0694",
            'Up' => "99.9306",
        ];

        $results->each(fn($series) => $this->assertTrue($series->percent === $expectedResults[$series->category]));
    }

    public function seed90DayData()
    {
        for ($i = 0; $i < 100; $i++) {
            $date = Carbon::now('UTC')->subDays($i)->format('Y-m-d');
            MonitorUptimeEventCount::factory()->create([
                'filter_date' => $date,
                'up' => 1438,
                'down' => 1,
                'recovered' => 1,
            ]);
        }
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

        foreach ($seedData as $date => $counts) {
            MonitorUptimeEventCount::factory()->create(array_merge([
                'filter_date' => $date
            ], $counts));
        }
    }
}
