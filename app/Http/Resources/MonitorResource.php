<?php

namespace App\Http\Resources;

use App\Models\Monitor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @mixin Monitor
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->hashId,
            'url' => $this->raw_url,
            'uptime_check_enabled' => $this->uptime_check_enabled,
            'look_for_string' => $this->look_for_string,
            'uptime_status' => $this->uptime_status,
            'certificate_check_enabled' => $this->certificate_check_enabled,
            'certificate_status' => $this->certificate_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'channels' => ChannelResource::collection($this->whenLoaded('channels')),
        ];
    }
}
