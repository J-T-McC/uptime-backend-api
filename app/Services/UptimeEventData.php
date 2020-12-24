<?php


namespace App\Services;

use App\Models\Enums\UptimeStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UptimeEventData
{

    protected $model;

    public function __construct($monitor = null)
    {
        $this->model = auth()->user()->uptimeEventCounts()->monitorFilter($monitor);
    }

    public function trendedMonthly()
    {
        return $this->model->select(
            DB::raw('SUM( up + recovered ) / SUM( up + recovered + down ) * 100  as percent'),
            'filter_year',
            'filter_week'
        )
            ->groupBy(
                'filter_year',
                'filter_week',
            )
            ->orderBy('filter_year', 'DESC')
            ->orderBy('filter_week', 'DESC')
            ->limit(10)
            ->get()->map(function ($series) {
                $carbon = new Carbon();
                $series->category = self::getWeeklyRangeCategory($carbon, $series->filter_year, $series->filter_week);
                return $series;
            });
    }

    public function past90Days()
    {

        $percentUp = clone $this->model->select(
            DB::raw('SUM( up + recovered ) / SUM( up + recovered + down ) * 100 as percent'),
            DB::raw('"Up" as category'),
            'filter_year',
            'filter_week'
        )->groupBy(
            'filter_year',
            'filter_week',
        )
            ->orderBy('filter_year', 'DESC')
            ->orderBy('filter_week', 'DESC')
            ->limit(1);

        $percentDown = $this->model->select(
            DB::raw('SUM( down ) / SUM( up + recovered + down ) * 100 as percent'),
            DB::raw('"Down" as category'),
            'filter_year',
            'filter_week'
        )->groupBy(
            'filter_year',
            'filter_week',
        )
            ->orderBy('filter_year', 'DESC')
            ->orderBy('filter_week', 'DESC')
            ->limit(1);

        return $percentUp->union($percentDown)->get();
//        DB::raw('SUM( down ) / SUM( up + recovered + down ) * 100 as percent_down'),
    }

    public static function getWeeklyRangeCategory(Carbon $carbon, $year, $week)
    {
        $format = 'M d';
        $carbon->setISODate($year, $week, 0);
        return $carbon
                ->startOfWeek(Carbon::SUNDAY)
                ->format($format) . ' - ' . $carbon->endOfWeek(Carbon::SATURDAY)->format($format);
    }

}
