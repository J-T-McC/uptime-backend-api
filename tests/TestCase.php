<?php

namespace Tests;

use EnricoStahn\JsonAssert\AssertClass as JsonAssert;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use JMac\Testing\Traits\AdditionalAssertions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, AdditionalAssertions;

    const BAD_SSL = 'https://expired.badssl.com';
    const VALID_SSL = 'https://google.com';
    const UPTIME_FAIL = 'https://test-response.tysonmccarney.com/500';
    const UPTIME_SUCCEED = 'https://test-response.tysonmccarney.com/200';
    const SCHEMA_ROOT = __DIR__ . '/Schemas/';

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

    /**
     * Assert response matches json schema
     *
     * @param TestResponse $response
     * @param string $schema
     */
    protected function assertResponseJson(TestResponse $response, string $schema)
    {
        JsonAssert::assertJsonMatchesSchema(self::getResponseData($response), self::SCHEMA_ROOT . $schema);
    }

    /**
     * Assert response collection matches json schema
     *
     * @param TestResponse $response
     * @param string $schema
     */
    protected function assertResponseCollectionJson(TestResponse $response, string $schema)
    {
        foreach (self::getResponseData($response) as $data) {
            JsonAssert::assertJsonMatchesSchema($data, self::SCHEMA_ROOT . $schema);
        }
    }

    protected static function getResponseData(TestResponse $response)
    {
        $data = json_decode($response->content());
        if (is_object($data) && property_exists($data, 'data')) {
            $data = $data->data;
        }

        return $data;
    }
}
