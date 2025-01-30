<?php

namespace App\Models;

use App\Models\Enums\Category;
use App\Models\Enums\CertificateStatus;
use App\Models\Enums\UptimeStatus;
use App\Models\Traits\UsesOwnerScope;
use App\Services\HashId\Traits\HasHashedId;
use Database\Factories\MonitorEventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorEvent extends Model
{
    /** @use HasFactory<MonitorEventFactory> */
    use HasFactory;
    use UsesOwnerScope;
    use HasHashedId;

    protected $fillable = [
        'category',
        'status',
        'error',
        'user_id'
    ];

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
    public function scopeMonitorFilter(Builder $query, ?Monitor $monitor = null): Builder
    {
        return $monitor ? $query->where('monitor_id', $monitor->id) : $query;
    }

    /**
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    public function scopeUptime(Builder $query): Builder
    {
        return $query->where('category', Category::UPTIME);
    }

    /**
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    public function scopeCertificate(Builder $query): Builder
    {
        return $query->where('category', Category::CERTIFICATE);
    }

    /**
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    public function scopeApplyStatusRequirements(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query
                ->where(function ($query) {
                    $query
                        ->where('category', Category::UPTIME)
                        ->whereIn('status', [
                            UptimeStatus::RECOVERED,
                            UptimeStatus::OFFLINE,
                        ]);
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('category', Category::CERTIFICATE)
                        ->whereIn('status', [
                            CertificateStatus::VALID,
                            CertificateStatus::INVALID,
                            CertificateStatus::EXPIRED,
                        ]);
                });
        });
    }
}
