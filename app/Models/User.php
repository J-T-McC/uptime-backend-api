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
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return HasMany<Monitor>
     */
    public function monitors(): HasMany
    {
        return $this->hasMany(Monitor::class);
    }

    /**
     * @return HasMany<MonitorEvent>
     */
    public function monitorEvents(): HasMany
    {
        return $this->hasMany(MonitorEvent::class);
    }

    /**
     * @return HasMany<MonitorUptimeEventCount>
     */
    public function uptimeEventCounts(): HasMany
    {
        return $this->hasMany(MonitorUptimeEventCount::class);
    }

    /**
     * @return HasMany<Channel>
     */
    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }
}
