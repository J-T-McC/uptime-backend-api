<?php

namespace App\Models;

use App\Models\Traits\UsesOwnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory, UsesOwnerScope;

    protected $fillable = [
        'endpoint',
        'secret',
        'type',
        'description',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function monitors()
    {
        return $this->belongsToMany(Monitor::class);
    }
}
