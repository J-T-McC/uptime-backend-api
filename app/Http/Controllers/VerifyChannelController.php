<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyChannelRequest;
use App\Models\Channel;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;

class VerifyChannelController extends Controller
{
    public function __invoke(VerifyChannelRequest $request): Response
    {
        /** @var string $encryptedChannelId */
        $encryptedChannelId = $request->route('channel');

        /** @var Channel $channel */
        $channel = Channel::query()->findOrFail(
            Crypt::decrypt($encryptedChannelId)
        );

        $channel->verified = true;
        $channel->save();

        return response()->noContent();
    }
}
