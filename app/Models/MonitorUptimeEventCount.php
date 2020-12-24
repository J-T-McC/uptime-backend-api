<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Traits\UsesOwnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitorUptimeEventCount extends Model
{
    use HasFactory, UsesOwnerScope;

    protected $fillable = ['monitor_id', 'user_id', 'filter_day', 'filter_week', 'filter_month', 'filter_year'];

    public function monitor() {
        return $this->belongsTo(Monitor::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeMonitorFilter($query, $monitor = null) {
        return $monitor ? $query->where('monitor_id', $monitor) : $query;
    }

    public static function getDateFilterValues() {
        $date = Carbon::now('UTC');
        return [
            'filter_day' => $date->day,
            'filter_week' => $date->week,
            'filter_month' => $date->month,
            'filter_year' => $date->year,
        ];
    }

}
