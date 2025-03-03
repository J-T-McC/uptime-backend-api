<?php

namespace App\Models;

use App\Enums\CrudAction;
use App\Services\HashId\Traits\HasHashedId;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasHashedId;
    use HasRolesAndPermissions;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
     * @return HasMany<Monitor, $this>
     */
    public function monitors(): HasMany
    {
        return $this->hasMany(Monitor::class);
    }

    /**
     * @return HasMany<MonitorEvent, $this>
     */
    public function monitorEvents(): HasMany
    {
        return $this->hasMany(MonitorEvent::class);
    }

    /**
     * @return HasMany<MonitorUptimeEventCount, $this>
     */
    public function uptimeEventCounts(): HasMany
    {
        return $this->hasMany(MonitorUptimeEventCount::class);
    }

    /**
     * @return HasMany<Channel, $this>
     */
    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }

    /**
     * Default admin panel permission method for Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAbleTo(\App\Enums\Permission::ACCESS_ADMINISTRATION_PANEL->value);
    }

    /**
     * Define if the user can access the Laravel pulse panel.
     */
    public function canAccessPulsePanel(): bool
    {
        return $this->isAbleTo(\App\Enums\Permission::ACCESS_PULSE_PANEL->value);
    }

    public function canPerformCrudAction(CrudAction $action, string $model): bool
    {
        /** @var Model $model */
        $model = (new $model);
        $table = $model->getTable();

        return $this->isAbleTo($table.'-'.$action->value);
    }
}
