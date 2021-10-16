<?php

namespace Tests;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

abstract class AuthenticatedTestCase extends TestCase
{
    protected User $testUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->testUser = User::factory()->create();

        Sanctum::actingAs($this->testUser);
    }
}
