<?php

namespace Tests\Unit\Enum;

use Tests\TestCase;

class UptimeStatus extends TestCase
{
    use EnumTestTrait;

    const expected = [
        'UNKNOWN' => 0,
        'ONLINE' => 1,
        'RECOVERED' => 2,
        'OFFLINE' => 3,
    ];

    const model = UptimeStatus::class;
}
