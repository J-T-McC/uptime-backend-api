<?php

namespace App\Models;

use App\Models\Enums\Category;
use App\Models\Enums\CertificateStatus;
use App\Models\Enums\UptimeStatus;
use App\Models\Traits\UsesOwnerScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorEvent extends Model
{
    use HasFactory, UsesOwnerScope;

    protected $fillable = [
        'category',
        'status',
        'error',
        'user_id'
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMonitorFilter(Builder $query, ?Monitor $monitor = null): Builder
    {
        return $monitor ? $query->where('monitor_id', $monitor->id) : $query;
    }

    public function scopeUptime(Builder $query): Builder
    {
        return $query->where('category', Category::UPTIME);
    }

    public function scopeCertificate(Builder $query): Builder
    {
        return $query->where('category', Category::CERTIFICATE);
    }

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
