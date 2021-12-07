<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    public function getKey(): string
    {
        return static::class;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function monitors(): HasMany
    {
        return $this->hasMany(Monitor::class);
    }

    public function monitorEvents(): HasMany
    {
        return $this->hasMany(MonitorEvent::class);
    }

    public function uptimeEventCounts(): HasMany
    {
        return $this->hasMany(MonitorUptimeEventCount::class);
    }

    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }
}
