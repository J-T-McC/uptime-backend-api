<?php

namespace App\Models;

use App\Models\Scopes\OwnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint',
        'secret',
        'type',
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

    public function monitors() {
        return $this->belongsToMany(Monitor::class, 'channel_monitor');
    }

}
