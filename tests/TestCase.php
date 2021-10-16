<?php

namespace Tests;

use JMac\Testing\Traits\AdditionalAssertions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, AdditionalAssertions;

    const badSSL = 'https://expired.badssl.com';
    const validSSL = 'https://google.com';
    const uptimeFail = 'https://test-response.tysonmccarney.com/500';
    const uptimeSucceed = 'https://test-response.tysonmccarney.com/200';

}
