<?php

namespace Tests\Unit\Enum;

use Tests\TestCase;

use App\Models\Enums\Category;

class CategoryTest extends TestCase
{

    use EnumTestTrait;

    const expected = [
        'UPTIME' => 1,
        'CERTIFICATE' => 2
    ];

    const model = Category::class;

}
