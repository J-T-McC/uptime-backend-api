<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->hashId,
            'description' => $this->description,
            'endpoint' => $this->endpoint,
            'type' => $this->type,
            'verified' => $this->verified,
            'pivot' => $this->when($this->pivot, $this->pivot),
        ];
    }
}
