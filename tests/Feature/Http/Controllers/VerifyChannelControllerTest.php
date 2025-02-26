<?php

namespace Http\Controllers;

use App\Actions\CreateSignedVerifyChannelUrl;
use App\Http\Controllers\VerifyChannelController;
use App\Http\Requests\VerifyChannelRequest;
use App\Models\Channel;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\AuthenticatedTestCase;

#[CoversClass(VerifyChannelController::class)]
#[CoversClass(VerifyChannelRequest::class)]
class VerifyChannelControllerTest extends AuthenticatedTestCase
{
    /**
     * @see VerifyChannelController::__invoke
     */
    public function test_it_verifies_channel()
    {
        // Collect
        $channel = Channel::factory()->for($this->testUser)->createQuietly([
            'verified' => false,
            'endpoint' => 'endpoint',
        ]);
        $url = (new CreateSignedVerifyChannelUrl)->handle($channel);

        // Act
        $response = $this->getJson($url);

        // Assert
        $response->assertNoContent();
        $channel->refresh();
        $this->assertTrue($channel->verified);
    }

    /**
     * @see VerifyChannelController::__invoke
     */
    public function test_it_fails_verification_if_endpoint_changes()
    {
        // Collect
        $channel = Channel::factory()->for($this->testUser)->createQuietly([
            'verified' => false,
            'endpoint' => 'old-endpoint',
        ]);
        $url = (new CreateSignedVerifyChannelUrl)->handle($channel);

        $channel->updateQuietly(['endpoint' => 'new-endpoint']);

        // Act
        $response = $this->getJson($url);

        // Assert
        $response->assertForbidden();
        $this->assertFalse($channel->verified);
    }

    /**
     * @see VerifyChannelController::__invoke
     * @see VerifyChannelRequest::authorize
     */
    public function test_it_rejects_invalid_signature()
    {
        // Collect
        $channel = Channel::factory()->for($this->testUser)->createQuietly([
            'verified' => false,
        ]);
        $url = (new CreateSignedVerifyChannelUrl)->handle($channel);
        $url = str_replace('signature=', 'signature=invalid', $url);

        // Act
        $response = $this->getJson($url);

        // Assert
        $response->assertForbidden();
        $channel->refresh();
        $this->assertFalse($channel->verified);
    }
}
