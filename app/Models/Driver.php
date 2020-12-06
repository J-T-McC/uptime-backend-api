<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint',
        'secret',
        'type',
    ];

    public function monitors() {
        return $this->belongsToMany(Monitor::class, 'driver_monitor');
    }

}
