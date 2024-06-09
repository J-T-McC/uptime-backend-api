<?php

namespace App\Models\Traits;

use App\Models\Scopes\OwnerScope;

trait UsesOwnerScope
{
    protected static function bootUsesOwnerScope(): void
    {
        if (!app()->runningInConsole()) {
            static::addGlobalScope(new OwnerScope());
        }
    }
}
