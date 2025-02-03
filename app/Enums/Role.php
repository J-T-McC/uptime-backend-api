<?php

namespace App\Enums;

use App\Enums\Attributes\Description;
use App\Enums\Attributes\DisplayName;
use App\Enums\Traits\HasMeta;

enum Role: string
{
    use HasMeta;

    #[Description('Site Administrator')]
    #[DisplayName('Administrator')]
    case ADMIN = 'admin';
}
