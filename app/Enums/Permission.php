<?php

namespace App\Enums;

use App\Enums\Attributes\Description;
use App\Enums\Attributes\DisplayName;
use App\Enums\Traits\HasMeta;

enum Permission: string
{
    use HasMeta;

    #[Description('Grants access to the administration panel')]
    #[DisplayName('Access Administration Panel')]
    case ACCESS_ADMINISTRATION_PANEL = 'access_administration_panel';

    #[Description('Grants access to the laravel pulse panel')]
    #[DisplayName('Access Pulse Panel')]
    case ACCESS_PULSE_PANEL = 'access_pulse_panel';
}
