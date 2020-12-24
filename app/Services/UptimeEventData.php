<?php


namespace App\Services;

use App\Models\MonitorUptimeEventCount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UptimeEventData
{

    protected $model;

    public function __construct($monitor = null)
    {
        $this->model = MonitorUptimeEventCount::monitorFilter($monitor);
    }

    public function trendedMonthly()
    {
        //TODO handle weeks that exist in two years
        return $this->model->select(
            DB::raw('ROUND(SUM( up + recovered ) / SUM( up + recovered + down ) * 100, 10)  as percent'),
            DB::raw('YEAR(filter_date) as filter_year'),
            //ISO 8601 week
            DB::raw('WEEK(filter_date, 3) as filter_week')
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
        $min = Carbon::now('UTC')->subDays(90)->format('Y-m-d');

        $percentUp = (clone $this->model)->select(
            DB::raw('ROUND(SUM( up + recovered ) / SUM( up + recovered + down ) * 100, 4) as percent'),
            DB::raw('"Up" as category')
        )->where('filter_date', '>', $min);

        $percentDown = (clone $this->model)->select(
            DB::raw('ROUND(SUM( down ) / SUM( up + recovered + down ) * 100, 4) as percent'),
            DB::raw('"Down" as category')
        )->where('filter_date', '>', $min);

        return $percentUp->union($percentDown)->get();
    }

    public static function getWeeklyRangeCategory(Carbon $carbon, $year, $week)
    {
        $format = 'M d';
        $carbon->setISODate($year, $week);
        return $carbon
                ->startOfWeek()
                ->format($format) . ' - ' . $carbon->endOfWeek()->format($format);
    }

}
