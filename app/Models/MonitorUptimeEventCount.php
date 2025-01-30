<?php

namespace App\Models;

use App\Models\Traits\UsesOwnerScope;
use App\Services\HashId\Traits\HasHashedId;
use Database\Factories\MonitorUptimeEventCountFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorUptimeEventCount extends Model
{
    /** @use HasFactory<MonitorUptimeEventCountFactory> */
    use HasFactory;
    use UsesOwnerScope;
    use HasHashedId;

    protected $fillable = ['monitor_id', 'user_id', 'filter_date'];

    /**
     * @return BelongsTo<Monitor, $this>
     */
    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param Builder<$this> $query
     * @param Monitor|null $monitor
     * @return Builder<$this>
     */
    public function scopeMonitorFilter(Builder $query, ?Monitor $monitor = null)
    {
        return $monitor ? $query->where('monitor_id', $monitor->id) : $query;
    }
}
