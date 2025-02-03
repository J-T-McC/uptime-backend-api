<?php

namespace App\Enums\Attributes;

use Attribute;

#[Attribute]
readonly class Description
{
    public function __construct(
        public string $description,
    ) {
    }
}
