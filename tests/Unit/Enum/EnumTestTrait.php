<?php

namespace Tests\Unit\Enum;

trait EnumTestTrait
{
    /**
     * @test
     */
    public function property_names_and_values_have_not_changed()
    {
        // Collect
        $class = self::model;
        $toMatch = [];
        foreach ($class::cases() as $case) {
            $toMatch[$case->name] = $case->value;
        }

        // Assert
        foreach (static::expected as $name => $value) {
            $this->assertTrue(empty(array_diff_assoc(static::expected, $toMatch)));
        }
    }
}
