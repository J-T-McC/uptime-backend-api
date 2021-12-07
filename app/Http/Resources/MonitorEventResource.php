<?php

namespace App\Http\Resources;

use App\Models\Enums\Category;
use App\Models\Enums\CertificateStatus;
use App\Models\Enums\UptimeStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorEventResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category' => Category::from($this->category)->name,
            'status' => $this->category === Category::CERTIFICATE ?
                CertificateStatus::from($this->status)->name :
                UptimeStatus::from($this->status)->name,
            'error' => $this->error,
            'created_at' => $this->created_at,
            'monitor' => new MonitorResource($this->monitor)
        ];
    }
}
