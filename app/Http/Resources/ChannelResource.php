<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $response = [
            'id' => $this->id,
            'description' => $this->description,
            'endpoint' => $this->endpoint,
            'type' => $this->type,
        ];

        if($this->pivot) {
            $response['pivot'] =  $this->pivot;
        }

        return $response;
    }
}
