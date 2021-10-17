<?php


namespace App\Models\Enums;

use ReflectionClass;
use ReflectionException;

class Enum
{
    private static ?array $constCacheArray = null;

    /**
     * @throws ReflectionException
     */
    public static function getConstants()
    {
        if (is_null(self::$constCacheArray)) {
            self::$constCacheArray = [];
        }

        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return self::$constCacheArray[$calledClass];
    }

    /**
     * @throws ReflectionException
     */
    public static function getNameFromValue(int $value)
    {
        return array_search($value, self::getConstants(), true) ?? null;
    }
}
