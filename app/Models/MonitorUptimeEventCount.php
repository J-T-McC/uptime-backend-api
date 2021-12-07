<?php

namespace App\Models;

use App\Models\Traits\UsesOwnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorUptimeEventCount extends Model
{
    use HasFactory;
    use UsesOwnerScope;

    protected $fillable = ['monitor_id', 'user_id', 'filter_date'];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMonitorFilter($query, ?Monitor $monitor = null)
    {
        return $monitor ? $query->where('monitor_id', $monitor->id) : $query;
    }
}
