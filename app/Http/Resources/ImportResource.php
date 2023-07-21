<?php

namespace App\Http\Resources;

use App\Models\Enums\ImportStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'staff_name' => $this->staff->name,
            'status' => ImportStatus::tryFrom($this->status)->label(),
            'propvider_id' => $this->provider_id,
            'provider_name' => $this->provider->name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}