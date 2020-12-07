<?php

namespace App\Http\Requests;

use App\Models\Driver;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class DriverRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => [
                'required',
                'string',
                Rule::in(config('uptime-monitor.notifications.integrated-services')),
                Rule::unique(app(Driver::class)->getTable())
                    ->where('user_id', $this->user()->id)
                    ->where('type', $this->input('type'))
                    ->where('endpoint', $this->input('endpoint'))
            ],
            'endpoint' => 'required|string',
        ];
    }
}
