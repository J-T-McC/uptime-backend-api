<?php


namespace App\Models\Enums;

use ReflectionClass;

class Enum
{
    private static $constCacheArray = null;

    private static function getConstants() {
        if (self::$constCacheArray == null) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function getNameFromValue(int $value) {
        return array_search($value, self::getConstants(), true) ?? null;
    }

}
