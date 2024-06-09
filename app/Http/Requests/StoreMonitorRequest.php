<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMonitorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'url' => [
                'required',
                'string',
                'url',
                Rule::unique('monitors')
                    ->where('user_id', $this->user()->id)
                    ->where('url', $this->input('url')),
                'active_url'
            ],
            'certificate_check_enabled' => 'boolean',
            'look_for_string' => 'string|nullable',
            'uptime_check_enabled' => 'boolean'
        ];
    }
}
