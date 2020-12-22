<?php

namespace App\Models\Enums;

class UptimeStatus extends Enum
{
    const UNKNOWN = 0;

    const ONLINE = 1;
    const RECOVERED = 2;
    const OFFLINE = 3;

    public static function getStatusFromName(string $name) {
        return [
            'up' => self::ONLINE,
            'recovered' => self::RECOVERED,
            'down' => self::OFFLINE,
        ][$name] ?? self::UNKNOWN;
    }
}
