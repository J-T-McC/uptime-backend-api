<?php

namespace App\Http\Requests;

use App\Models\Channel;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ChannelRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = $this->route()->parameter('channel');

        $type = $this->input('type');

        $uniqueRule = Rule::unique(app(Channel::class)->getTable())
            ->where('user_id', $this->user()->id)
            ->where('type', $type)
            ->where('endpoint', $this->input('endpoint'));

        $uniqueRule = !empty($id) ? $uniqueRule->whereNot('id', $id) : $uniqueRule;

        $endpointRules = config('uptime-monitor.notifications.service-endpoint-rules.' . $type, '');

        return [
            'type' => [
                'required',
                'string',
                Rule::in(array_keys(config('uptime-monitor.notifications.service-endpoint-rules'))),
                $uniqueRule
            ],
            'endpoint' => 'required|string' . $endpointRules,
            'description' => 'string|nullable',
        ];
    }
}
