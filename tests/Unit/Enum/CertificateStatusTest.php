<?php

namespace Tests\Unit\Enum;

use App\Enums\CertificateStatus;
use Tests\TestCase;

class CertificateStatusTest extends TestCase
{
    use EnumTestTrait;

    const expected = [
        'UNKNOWN' => 0,
        'VALID' => 1,
        'INVALID' => 2,
        'EXPIRED' => 3,
    ];

    const model = CertificateStatus::class;
}
