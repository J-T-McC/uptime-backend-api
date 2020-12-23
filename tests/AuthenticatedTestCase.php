<?php

namespace Tests;

abstract class AuthenticatedTestCase extends TestCase
{

    protected $testUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->testUser = \App\Models\User::factory()->create();

        $this->actingAs($this->testUser);
    }

}
