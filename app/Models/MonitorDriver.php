<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitorDriver extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint',
        'secret',
        'type',
    ];

    public function monitor() {
        return $this->belongsTo(Monitor::class);
    }

}
