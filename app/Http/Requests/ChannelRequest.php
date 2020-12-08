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

        $id = $this->route()->parameter('monitor');

        $uniqueRule = Rule::unique(app(Channel::class)->getTable())
            ->where('user_id', $this->user()->id)
            ->where('type', $this->input('type'))
            ->where('endpoint', $this->input('endpoint'));

        $uniqueRule = !empty($id) ? $uniqueRule->whereNot('id', $id) : $uniqueRule;

        return [
            'type' => [
                'required',
                'string',
                Rule::in(config('uptime-monitor.notifications.integrated-services')),
                $uniqueRule
            ],
            'endpoint' => 'required|string',
        ];
    }
}
