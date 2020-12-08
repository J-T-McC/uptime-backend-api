<?php

namespace App\Http\Requests;

use App\Models\Monitor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MonitorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = $this->route()->parameter('monitor');

        $uniqueRule = Rule::unique(app(Monitor::class)->getTable())
            ->where('user_id', $this->user()->id)
            ->where('url', $this->input('url'));

        $uniqueRule = !empty($id) ? $uniqueRule->whereNot('id', $id) : $uniqueRule;

        return [
            'url' => [
                'required',
                'string',
                'url',
                $uniqueRule,
                'active_url'
            ],
            'certificate_check_enabled' => 'boolean',
            'look_for_string' => 'string',
            'uptime_check_enabled' => 'boolean'
        ];
    }
}
