<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonitorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => 'required|string|url|active_url',
            'certificate_check_enabled' => 'boolean',
            'look_for_string' => 'string',
            'uptime_check_enabled' => 'boolean'
        ];
    }
}
