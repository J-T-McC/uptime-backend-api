<?php

namespace App\Services;

use App\Models\Monitor;
use App\Models\MonitorUptimeEventCount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UptimeEventData
{
    protected Builder $query;

    public function __construct(?Monitor $monitor = null)
    {
        $this->query = MonitorUptimeEventCount::query()->monitorFilter($monitor);
    }

    public function trended()
    {
        return $this->query
            ->select(
                DB::raw('ROUND(SUM( up + recovered ) / SUM( up + recovered + down ) * 100, 10)  as percent'),
                DB::raw('DATE_FORMAT(filter_date, "%b %D") as category'),
                'filter_date'
            )
            ->groupBy(
                'filter_date',
                'category',
            )
            ->orderBy('filter_date', 'DESC')
            ->limit(14)
            ->get();
    }

    public function past90Days()
    {
        $min = Carbon::now('UTC')->subDays(90)->format('Y-m-d');

        $percentUp = (clone $this->query)->select(
            DB::raw('ROUND(SUM( up + recovered ) / SUM( up + recovered + down ) * 100, 4) as percent'),
            DB::raw('"Up" as category')
        )->where('filter_date', '>', $min);

        $percentDown = (clone $this->query)->select(
            DB::raw('ROUND(SUM( down ) / SUM( up + recovered + down ) * 100, 4) as percent'),
            DB::raw('"Down" as category')
        )->where('filter_date', '>', $min);

        return $percentUp->union($percentDown)->get();
    }
}
