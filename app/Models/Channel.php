<?php

namespace App\Models;

use App\Events\ChannelCreated;
use App\Events\ChannelUpdated;
use App\Services\HashId\Traits\HasHashedId;
use Database\Factories\ChannelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Channel extends Model
{
    /** @use HasFactory<ChannelFactory> */
    use HasFactory;

    use HasHashedId;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'endpoint',
        'secret',
        'type',
        'description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'verified' => 'boolean',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'verified' => false,
    ];

    /**
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => ChannelCreated::class,
        'updated' => ChannelUpdated::class,
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Monitor, $this>
     */
    public function monitors(): BelongsToMany
    {
        return $this->belongsToMany(Monitor::class)->withTimestamps();
    }
}
