<?php

namespace Tests\Feature\Services\HashId\Traits;

use App\Services\HashId\HashId;
use App\Services\HashId\Traits\HasHashedId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\CoversTrait;
use Tests\TestCase;

#[CoversTrait(HasHashedId::class)]
class HasHashedIdTest extends TestCase
{
    /**
     * @see HasHashedId::hashId
     */
    public function test_it_returns_correct_string_for_numeric_id(): void
    {
        // Create an anonymous class with the HasHashedId trait
        $model = new class extends Model
        {
            use HasHashedId;

            public int $id = 123;
        };

        // Assert
        $this->assertEquals((new HashId)->encode(123), $model->hashId);
    }

    /**
     * @see HasHashedId::resolveRouteBindingQuery
     */
    public function test_it_correctly_decodes_a_squid_via_route_model_binding(): void
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
        $model = new class extends Model
        {
            use HasHashedId;

            public function newQuery(): Builder
            {
                return app(Builder::class);
            }
        };

        // Act
        $model->resolveRouteBindingQuery($model->newQuery(), (new HashId)->encode(123));
    }
}
