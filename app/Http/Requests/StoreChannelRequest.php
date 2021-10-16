<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StoreChannelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $type = $this->input('type');

        $endpointRules = config('uptime-monitor.notifications.service-endpoint-rules.' . $type, '');

        return [
            'type' => [
                'required',
                'string',
                Rule::in(array_keys(config('uptime-monitor.notifications.service-endpoint-rules'))),
                Rule::unique('channels')
                    ->where('user_id', $this->user()->id)
                    ->where('type', $type)
                    ->where('endpoint', $this->input('endpoint'))
            ],
            'endpoint' => 'required|string' . $endpointRules,
            'description' => 'string|nullable',
        ];
    }
}
