<?php

namespace App\Models\Enums;

class CertificateStatus extends Enum
{
    const UNKNOWN = 0;

    const VALID = 1;
    const INVALID = 2;
    const EXPIRED = 3;

    public static function getStatusFromName(string $name) {
        return [
            'valid' => self::VALID,
            'invalid' => self::INVALID,
            'expired' => self::EXPIRED
        ][$name] ?? self::UNKNOWN;
    }
}
