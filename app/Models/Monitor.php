<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Spatie\UptimeMonitor\Models\Monitor as SpatieMonitor;

class Monitor extends SpatieMonitor
{
    use HasFactory;

    protected $fillable = [
        'url',
        'uptime_check_enabled',
        'certificate_check_enabled',
        'look_for_string'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function drivers() {
        return $this->belongsToMany(Driver::class);
    }

    /**
     * @param Builder $query
     */
    public function scopeOwned(Builder $query)
    {
        $query->where('user_id', auth()->user()->id);
    }

    /**
     * Prevent extended class's duplicate logic from running
     * @param SpatieMonitor $monitor
     * @return bool
     */
    protected static function alreadyExists(SpatieMonitor $monitor): bool
    {
       return false;
    }
}
