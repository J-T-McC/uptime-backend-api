<?php

namespace App\Http\Resources;

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
            'look_for_string' => $this->look_for_string,
            'uptime_status' => $this->uptime_status,
            'certificate_check_enabled' => $this->certificate_check_enabled,
            'certificate_status' => $this->look_for_string,
            'channels' => ChannelResource::collection($this->channels)
        ];
    }
}
