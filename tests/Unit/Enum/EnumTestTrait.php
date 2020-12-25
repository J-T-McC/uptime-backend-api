<?php

namespace Tests\Unit\Enum;

trait EnumTestTrait
{

    /**
     * @test
     */
    public function property_names_and_values_have_not_changed()
    {
        $class = self::model;
        $enum = new $class();
        $this->assertTrue(empty(array_diff_assoc(static::expected, $enum->getConstants())));
    }

    /**
     * @test
     */
    public function can_get_property_from_value()
    {
        $class = self::model;
        $enum = new $class();
        $props = $enum->getConstants();
        $category = array_keys($props)[0];
        $value = array_values($props)[0];
        $this->assertTrue($enum->getNameFromValue($value) === $category);
    }

}
