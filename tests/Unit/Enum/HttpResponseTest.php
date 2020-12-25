<?php

namespace Tests\Unit\Enum;

use Tests\TestCase;

use App\Models\Enums\HttpResponse;

class HttpResponseTest extends TestCase
{

    use EnumTestTrait;

    const expected = [
        "SUCCESSFUL" => 200,
        "CREATED" => 201,
        "ACCEPTED" => 202,
        "PERMANENT_REDIRECT" => 301,
        "REDIRECT" => 302,
        "BAD_REQUEST" => 400,
        "UNAUTHORISED" => 401,
        "FORBIDDEN" => 403,
        "NOT_FOUND" => 404,
        "METHOD_NOT_ALLOWED" => 405,
        "CONFLICT" => 409,
        "UNPROCESSABLE_ENTITY" => 422,
        "INTERNAL_SERVER_ERROR" => 500,
    ];

    const model = HttpResponse::class;

}
