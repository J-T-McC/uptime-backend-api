<?php

namespace App\Models\Enums;

enum CertificateStatus: int
{
    case UNKNOWN = 0;
    case VALID = 1;
    case INVALID = 2;
    case EXPIRED = 3;

    public static function getStatusFromName(string $name): self
    {
        return [
                'valid' => self::VALID,
                'invalid' => self::INVALID,
                'expired' => self::EXPIRED
            ][$name] ?? self::UNKNOWN;
    }
}
