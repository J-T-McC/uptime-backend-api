<?php

namespace Tests\Feature\Services\HashId;

use App\Services\HashId\HashId;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\HashId\HashId
 */
class HashIdTest extends TestCase
{
    protected HashId $hashId;

    public function setUp(): void
    {
        parent::setUp();

        $this->hashId = new HashId();
    }

    /**
     * @test
     * @covers ::encode
     */
    public function it_encodes_an_id(): void
    {
        // Collect
        $id = 123;

        // Act
        $encoded = $this->hashId->encode($id);

        // Assert
        $this->assertIsString($encoded);
        $this->assertNotEmpty($encoded);
        $this->assertFalse(is_numeric($encoded));
    }

    /**
     * @test
     * @covers ::decode
     */
    public function it_decodes_an_encoded_id(): void
    {
        // Collect
        $id = 123;
        $encoded = $this->hashId->encode($id);

        // Act
        $decoded = $this->hashId->decode($encoded);

        // Assert
        $this->assertIsInt($decoded);
        $this->assertEquals($id, $decoded);
    }

    /**
     * @test
     * @covers ::decode
     */
    public function it_decodes_a_numeric_string(): void
    {
        // Collect
        $numericString = '123';

        // Act
        $decoded = $this->hashId->decode($numericString);

        // Assert
        $this->assertIsInt($decoded);
        $this->assertEquals(123, $decoded);
    }

    /**
     * @test
     * @covers ::decode
     */
    public function it_returns_null_for_invalid_string(): void
    {
        // Collect
        $invalidString = '';

        // Act
        $decoded = $this->hashId->decode($invalidString);

        // Assert
        $this->assertNull($decoded);
    }
}
