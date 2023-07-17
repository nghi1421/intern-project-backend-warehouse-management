<?php

namespace App\Http\Resources;

use App\Models\Enums\Gender;
use App\Models\Enums\WorkingStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
            'position' => $this->position->name,
            'phone_number' => $this->phone_number,
            'gender' => Gender::tryFrom($this->gender)->label(),
            'status' => WorkingStatus::tryFrom($this->working)->label(),
        ];
    }
}
