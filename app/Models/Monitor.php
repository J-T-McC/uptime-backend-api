<?php

namespace App\Models;

use App\Events\IncrementUptimeCount;
use App\Models\Traits\UsesOwnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\UptimeMonitor\Models\Monitor as SpatieMonitor;

class Monitor extends SpatieMonitor
{
    use HasFactory, UsesOwnerScope;

    protected $fillable = [
        'url',
        'uptime_check_enabled',
        'certificate_check_enabled',
        'look_for_string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class);
    }

    public function monitorEvents(): HasMany
    {
        return $this->hasMany(MonitorEvent::class);
    }

    public function uptimeEventCounts(): HasMany
    {
        return $this->hasMany(MonitorUptimeEventCount::class);
    }

    /**
     * Override parents duplication logic
     * Original prevents different users from monitoring the same url
     * @param SpatieMonitor $monitor
     * @return bool
     */
    protected static function alreadyExists(SpatieMonitor $monitor): bool
    {
        return false;
    }

    /**
     * Spatie event library does not fire event for repeated failures
     * This allows us to record the additional failures for our counts
     * See @Spatie\UptimeMonitor\Models\Traits\SupportsUptimeCheck
     * @param string $reason
     */
    public function uptimeCheckFailed(string $reason): void
    {
        parent::uptimeCheckFailed($reason);

        if (!$this->shouldFireUptimeCheckFailedEvent()) {
            event(new IncrementUptimeCount($this));
        }
    }
}
