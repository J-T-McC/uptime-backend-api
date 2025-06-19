<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;

abstract class TestCase extends BaseTestCase
{
    use AdditionalAssertions, FasterRefreshDatabase;

    const SCHEMA_ROOT = __DIR__.'/schemas/';

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    /**
     * Test form validation rules for a route
     */
    protected function assertRequestRules(
        string $route,
        array $requestBody,
        string|array $responseKey,
        string $method = 'postJson'
    ): void {
        /**
         * @var TestResponse $response
         */
        $response = $this->{$method}($route, $requestBody);

        try {
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors($responseKey);
        } catch (ExpectationFailedException|AssertionFailedError $e) {
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
