<?php

namespace App\Models\Traits;

use App\Models\Scopes\OwnerScope;

trait UsesOwnerScope
{
    protected static function bootUsesOwnerScope()
    {
        if (!app()->runningInConsole()) {
            static::addGlobalScope(new OwnerScope());
        }
    }
}
