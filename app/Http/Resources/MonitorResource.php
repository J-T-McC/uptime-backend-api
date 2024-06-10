<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->raw_url,
            'uptime_check_enabled' => $this->uptime_check_enabled,
            'look_for_string' => $this->look_for_string,
            'uptime_status' => $this->uptime_status,
            'certificate_check_enabled' => $this->certificate_check_enabled,
            'certificate_status' => $this->certificate_status,
            'channels' => ChannelResource::collection($this->whenLoaded('channels'))
        ];
    }
}
