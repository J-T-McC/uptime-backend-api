<?php

namespace App\Models\Enums\Attributes;

use Attribute;

#[Attribute]
readonly class DisplayName
{
    public function __construct(
        public string $displayName,
    ) {
    }
}
