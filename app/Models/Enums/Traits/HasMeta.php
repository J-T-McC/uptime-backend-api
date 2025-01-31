<?php

namespace App\Models\Enums\Traits;

use ReflectionClassConstant;

trait HasMeta
{
    public function getMeta(string $attribute): string
    {
        $ref = new ReflectionClassConstant(self::class, $this->name);
        $classAttributes = $ref->getAttributes($attribute);

        if (empty($classAttributes)) {
            return '';
        }

        return $classAttributes[0]->getArguments()[0] ?? '';
    }
}
