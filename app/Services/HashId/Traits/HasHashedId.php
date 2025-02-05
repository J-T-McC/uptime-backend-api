<?php

namespace App\Services\HashId\Traits;

use App\Services\HashId\HashId;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasHashedId
{
    public function resolveRouteBindingQuery($query, $value, $field = null): Builder
    {
        return parent::resolveRouteBindingQuery($query, (new HashId)->decode($value), $field);
    }

    /**
     * @return Attribute<string, never>
     */
    public function hashId(): Attribute
    {
        return Attribute::make(
            get: fn () => (new HashId)->encode($this->id),
        );
    }
}
