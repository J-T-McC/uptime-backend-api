<?php

namespace App\Models;

use App\Models\Scopes\OwnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected static function booted()
    {
        if(!app()->runningInConsole()) {
            static::addGlobalScope(new OwnerScope);
        }
        parent::booted();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function channels() {
        return $this->belongsToMany(Channel::class);
    }

    /**
     * Override parents unwanted duplication logic
     * @param SpatieMonitor $monitor
     * @return bool
     */
    protected static function alreadyExists(SpatieMonitor $monitor): bool
    {
       return false;
    }
}
