<?php

namespace App\Http\Resources;

use App\Models\Enums\Category;
use App\Models\Enums\CertificateStatus;
use App\Models\Enums\UptimeStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorEventResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category' => Category::getNameFromValue($this->category),
            'status' => $this->category === Category::CERTIFICATE ?
                CertificateStatus::getNameFromValue($this->status) :
                UptimeStatus::getNameFromValue($this->status),
            'error' => $this->error,
            'created_at' => $this->created_at,
            'monitor' => new MonitorResource($this->monitor)
        ];
    }
}
