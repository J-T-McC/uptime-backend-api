<?php

namespace App\Models;

use App\Events\IncrementUptimeCount;
use App\Services\HashId\Traits\HasHashedId;
use Database\Factories\MonitorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\UptimeMonitor\Models\Monitor as SpatieMonitor;

class Monitor extends SpatieMonitor
{
    /** @use HasFactory<MonitorFactory> */
    use HasFactory;

    use HasHashedId;

    protected $fillable = [
        'url',
        'user_id',
        'uptime_check_enabled',
        'certificate_check_enabled',
        'look_for_string',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Channel, $this>
     */
    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany<Channel, $this>
     */
    public function verifiedChannels(): BelongsToMany
    {
        return $this->channels()->where('channels.verified', true);
    }

    /**
     * @return HasMany<MonitorEvent, $this>
     */
    public function monitorEvents(): HasMany
    {
        return $this->hasMany(MonitorEvent::class);
    }

    /**
     * @return HasMany<MonitorUptimeEventCount, $this>
     */
    public function uptimeEventCounts(): HasMany
    {
        return $this->hasMany(MonitorUptimeEventCount::class);
    }

    /**
     * Override parents duplication logic
     * Original prevents different users from monitoring the same url
     */
    protected static function alreadyExists(SpatieMonitor $monitor): bool
    {
        return false;
    }

    /**
     * Spatie event library does not fire event for repeated failures
     * This allows us to record the additional failures for our counts
     * See @Spatie\UptimeMonitor\Models\Traits\SupportsUptimeCheck
     */
    public function uptimeCheckFailed(string $reason): void
    {
        parent::uptimeCheckFailed($reason);

        if (! $this->shouldFireUptimeCheckFailedEvent()) {
            event(new IncrementUptimeCount($this));
        }
    }
}
