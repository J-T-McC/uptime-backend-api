<?php

namespace App\Models;

use App\Events\ChannelCreated;
use App\Models\Traits\UsesOwnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Channel extends Model
{
    use HasFactory;
    use UsesOwnerScope;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'endpoint',
        'secret',
        'type',
        'description',
    ];

    protected $casts = [
        'verified' => 'boolean',
    ];

    protected $attributes = [
        'verified' => false,
    ];

    /**
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => ChannelCreated::class,
    ];

    /**
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Monitor>
     */
    public function monitors(): BelongsToMany
    {
        return $this->belongsToMany(Monitor::class);
    }
}
