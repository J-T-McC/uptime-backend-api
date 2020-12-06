<?php

/**
 * Common HTTP response codes
 */

namespace App\Models\Enums;

class HttpResponse extends Enum
{
    const SUCCESSFUL = 200;
    const CREATED = 201;
    CONST ACCEPTED = 202;

    const PERMANENT_REDIRECT = 301;
    const REDIRECT = 302;

    const BAD_REQUEST = 400;
    const UNAUTHORISED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const CONFLICT = 409;
    const UNPROCESSABLE_ENTITY = 422;

    const INTERNAL_SERVER_ERROR = 500;
}
