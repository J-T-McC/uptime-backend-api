<?php

namespace App\Enums;

enum UptimeStatus: int
{
    case UNKNOWN = 0;
    case ONLINE = 1;
    case RECOVERED = 2;
    case OFFLINE = 3;

    public static function getStatusFromName(string $name): self
    {
        return [
            'up' => self::ONLINE,
            'recovered' => self::RECOVERED,
            'down' => self::OFFLINE,
        ][$name] ?? self::UNKNOWN;
    }
}
