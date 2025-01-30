<?php

namespace App\Services\HashId\Traits;

use App\Services\HashId\HashId;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

trait HasHashedId
{
    public function resolveRouteBindingQuery($query, $value, $field = null): Builder
    {
        return parent::resolveRouteBindingQuery($query, (new HashId())->decode($value), $field);
    }

    public function hashId(): Attribute
    {
        return Attribute::make(
            get: fn () => (new HashId())->encode($this->id),
        );
    }
}
