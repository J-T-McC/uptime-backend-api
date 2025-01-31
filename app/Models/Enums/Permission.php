<?php

namespace App\Models\Enums;

use App\Models\Enums\Attributes\Description;
use App\Models\Enums\Attributes\DisplayName;
use App\Models\Enums\Traits\HasMeta;

enum Permission: string
{
    use HasMeta;

    #[Description('Grants access to the administration panel')]
    #[DisplayName('Access Administration Panel')]
    case ACCESS_ADMINISTRATION_PANEL = 'access_administration_panel';
}
