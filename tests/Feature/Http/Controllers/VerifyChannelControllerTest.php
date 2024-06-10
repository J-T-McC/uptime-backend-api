<?php

namespace Http\Controllers;

use App\Actions\CreateSignedVerifyChannelUrl;
use App\Models\Channel;
use Tests\AuthenticatedTestCase;

/**
 * @coversDefaultClass  \App\Http\Controllers\VerifyChannelController
 */
class VerifyChannelControllerTest extends AuthenticatedTestCase
{
    /**
     * @test
     * @covers ::__invoke
     */
    public function it_verifies_channel()
    {
        // Collect
        $channel = Channel::factory()->createQuietly([
            'verified' => false,
        ]);
        $url = (new CreateSignedVerifyChannelUrl())->handle($channel);

        // Act
        $response = $this->getJson($url);

        // Assert
        $response->assertNoContent();
        $channel->refresh();
        $this->assertTrue($channel->verified);
    }

    /**
     * @test
     * @covers ::__invoke
     * @covers \App\Http\Requests\VerifyChannelRequest::authorize
     */
    public function it_rejects_invalid_signature()
    {
        // Collect
        $channel = Channel::factory()->createQuietly([
            'verified' => false,
        ]);
        $url = (new CreateSignedVerifyChannelUrl())->handle($channel);
        $url = str_replace('signature=', 'signature=invalid', $url);

        // Act
        $response = $this->getJson($url);

        // Assert
        $response->assertForbidden();
        $channel->refresh();
        $this->assertFalse($channel->verified);
    }
}
