<?php

namespace Tests\Unit\Enum;

use App\Enums\Category;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use EnumTestTrait;

    const expected = [
        'UPTIME' => 1,
        'CERTIFICATE' => 2
    ];

    const model = Category::class;
}
