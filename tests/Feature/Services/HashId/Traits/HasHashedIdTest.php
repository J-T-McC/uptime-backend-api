<?php

namespace Tests\Feature\Services\HashId\Traits;

use App\Services\HashId\HashId;
use App\Services\HashId\Traits\HasHashedId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\HashId\Traits\HasHashedId
 */
class HasHashedIdTest extends TestCase
{
    /**
     * @test
     * @covers ::hashId
     */
    public function it_returns_correct_string_for_numeric_id(): void
    {
        // Create an anonymous class with the HasHashedId trait
        $model = new class extends Model {
            use HasHashedId;

            public int $id = 123;
        };

        // Assert
        $this->assertEquals((new HashId())->encode(123), $model->hashId);
    }

    /**
     * @test
     * @covers ::resolveRouteBindingQuery
     */
    public function it_correctly_decodes_a_squid_via_route_model_binding(): void
    {
        // Create a mock for the Builder class
        $builderMock = $this->mock(Builder::class);

        // Expect the where method to be called with specific arguments
        $builderMock
            ->shouldReceive('where')
            ->once()
            ->with(
                self::equalTo('id'),
                self::equalTo(123)
            )
            ->andReturnSelf();


        // Create an anonymous class with the HasHashedId trait
        $model = new class extends Model {
            use HasHashedId;

            public function newQuery(): Builder
            {
                return app(Builder::class);
            }
        };

        // Act
        $model->resolveRouteBindingQuery($model->newQuery(), (new HashId())->encode(123));
    }
}
