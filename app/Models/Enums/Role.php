<?php

namespace App\Models\Enums;

use App\Models\Enums\Attributes\Description;
use App\Models\Enums\Attributes\DisplayName;
use App\Models\Enums\Traits\HasMeta;

enum Role: string
{
    use HasMeta;

    #[Description('Site Administrator')]
    #[DisplayName('Administrator')]
    case ADMIN = 'admin';
}
