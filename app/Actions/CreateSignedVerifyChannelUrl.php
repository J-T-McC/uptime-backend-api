<?php

namespace App\Actions;

use App\Models\Channel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class CreateSignedVerifyChannelUrl
{
    public const int EXPIRATION_MINUTES = 60;

    public function handle(Channel $channel): string
    {
        return URL::temporarySignedRoute(
            name: 'verification.channel',
            expiration: Carbon::now()->addMinutes(self::EXPIRATION_MINUTES),
            parameters: [
                'channel' => Crypt::encrypt($channel->id),
                'endpoint' => sha1($channel->endpoint),
            ]
        );
    }
}
