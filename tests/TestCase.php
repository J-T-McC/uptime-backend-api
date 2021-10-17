<?php

namespace Tests;

use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use JMac\Testing\Traits\AdditionalAssertions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, AdditionalAssertions;

    const badSSL = 'https://expired.badssl.com';
    const validSSL = 'https://google.com';
    const uptimeFail = 'https://test-response.tysonmccarney.com/500';
    const uptimeSucceed = 'https://test-response.tysonmccarney.com/200';

    /**
     * Test form validation rules for a route
     *
     * @param string $route
     * @param array $requestBody
     * @param string|array $responseKey
     * @param string $method
     */
    protected function assertRequestRules(string $route, array $requestBody, string|array $responseKey, string $method = 'postJson'): void
    {
        /**
         * @var TestResponse $response
         */
        $response = $this->{$method}($route, $requestBody);

        try {
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors($responseKey);
        } catch (ExpectationFailedException | AssertionFailedError $e) {
            $message = $e->getMessage();
            $location = collect($e->getTrace())
                    ->where('function', 'assertFormValidationRule')
                    ->first() ?? [];

            $file = $location['file'] ?? '';
            $line = $location['line'] ?? '';

            $this->fail(
                <<<EOT
                <comment>
                $file:$line
                </comment>
                $message
                EOT
            );
        }
    }
}
