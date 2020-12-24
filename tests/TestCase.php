<?php

namespace Tests;

use JMac\Testing\Traits\AdditionalAssertions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, AdditionalAssertions;

    const badSSL = 'https://expired.badssl.com';
    const validSSL = 'https://google.com';
    const uptimeFail = 'https://httpstat.us/500';
    const uptimeSucceed = 'https://httpstat.us/200';


}
