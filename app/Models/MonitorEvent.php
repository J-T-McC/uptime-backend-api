<?php

namespace App\Models;

use App\Models\Enums\Category;
use App\Models\Traits\UsesOwnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MonitorEvent extends Model
{
    use HasFactory, UsesOwnerScope;

    protected $fillable = [
        'category',
        'status',
        'error',
        'user_id'
    ];

    public function monitor() {
        return $this->belongsTo(Monitor::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeMonitorFilter($query, $monitor = null) {
        return $monitor ? $query->where('monitor_id', $monitor) : $query;
    }

    public function scopeUptime($query) {
        return$query->where('category', Category::UPTIME);
    }

    public function scopeCertificate($query) {
        return$query->where('category', Category::CERTIFICATE);
    }
}
