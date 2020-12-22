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
        $this->model = auth()->user()->monitorEvents()->uptime()->monitorFilter($monitor);
    }

    public function trendedMonthly()
    {
        return $this->model->select(
            DB::raw('CAST(ROUND((SUM(IF(`status` IN (1, 2), 1, 0)) / COUNT(*)) * 100) as UNSIGNED) as percent'),
            DB::raw('YEAR(monitor_events.created_at) AS event_year'),
            DB::raw('WEEK(monitor_events.created_at, 0) + 1 AS event_week')
        )
            ->groupBy(
                'event_year',
                'event_week',
            )
            ->orderBy('event_year')
            ->orderBy('event_year', 'DESC')
            ->orderBy('event_week', 'DESC')
            ->limit(10)
            ->get()->map(function ($series) {
                $carbon = new Carbon();
                $series->category = self::getWeeklyRangeCategory($carbon, $series->event_year, $series->event_week);
                return $series;
            });
    }

    public function past90Days()
    {

        $baseQuery = clone $this->model;

        $range = 'monitor_events.created_at >= (NOW() - INTERVAL 90 DAY)';

        $baseQuery = $baseQuery->selectRaw('COUNT(*) AS total')->whereRaw($range);

        return $this->model->select(
            'status',
            DB::raw('CAST(ROUND((COUNT(*) / base.total) * 100) as UNSIGNED) AS percent'),
            DB::raw('base.total as total'),
        )->crossJoinSub($baseQuery, 'base')
            ->whereRaw($range)
            ->groupBy(
                'total',
                'status',
            )
            ->orderBy('status')
            ->get()->map(function($point) {
                $point->category = UptimeStatus::getNameFromValue($point->status);
                return $point;
            });
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
