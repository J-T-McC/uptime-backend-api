<?php

namespace App\Http\Requests;

use App\Models\Monitor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UpdateMonitorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, Unique|string>|string>
     */
    public function rules(): array
    {
        /** @var Monitor $monitor */
        $monitor = $this->route('monitor');

        return [
            'url' => [
                'required',
                'string',
                'url',
                Rule::unique('monitors')
                    ->where('user_id', $this->user()->id)
                    ->where('url', $this->input('url'))
                    ->whereNot('id', (string)$monitor->id),
                'active_url'
            ],
            'certificate_check_enabled' => 'boolean',
            'look_for_string' => 'string|nullable',
            'uptime_check_enabled' => 'boolean'
        ];
    }
}
