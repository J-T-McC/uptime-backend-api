<?php

namespace App\Models;

use App\Models\Traits\UsesOwnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitorUptimeEventCount extends Model
{
    use HasFactory, UsesOwnerScope;

    protected $fillable = ['monitor_id', 'user_id', 'filter_date'];

    public function monitor() {
        return $this->belongsTo(Monitor::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeMonitorFilter($query, $monitor = null) {
        return $monitor ? $query->where('monitor_id', $monitor) : $query;
    }

}
