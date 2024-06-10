<?php

namespace App\Http\Requests;

use App\Models\Channel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class VerifyChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (!$this->hasValidSignature()) {
            return false;
        }

        /** @var string $encryptedChannelId */
        $encryptedChannelId = $this->route('channel');

        /** @var Channel $channel */
        $channel = Channel::query()->findOrFail(
            Crypt::decrypt($encryptedChannelId)
        );

        return sha1($channel->endpoint) === $this->route('endpoint');
    }
}
