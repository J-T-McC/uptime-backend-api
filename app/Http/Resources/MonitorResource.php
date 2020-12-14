<?php

namespace App\Http\Resources;

use App\Http\Resources\ChannelResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url' => $this->raw_url,
            'uptime_check_enabled' => $this->uptime_check_enabled,
            'certificate_check_enabled' => $this->certificate_check_enabled,
            'uptime_status' => $this->uptime_status,
            'look_for_string' => $this->look_for_string,
            'channels' => ChannelResource::collection($this->channels)
        ];
    }
}
