<?php

namespace Tests\Unit\Enum;

use Tests\TestCase;

use App\Models\Enums\CertificateStatus;

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
