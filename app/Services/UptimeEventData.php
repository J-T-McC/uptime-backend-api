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

    public function trended()
    {
        return $this->model->select(
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
