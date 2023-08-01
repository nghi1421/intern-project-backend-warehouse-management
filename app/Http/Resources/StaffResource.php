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
            'position_id' => $this->position_id,
            'position' => $this->position->name,
            'user_id' => $this->user_id,
            'warehouse_branch_id' => $this->warehouse_branch_id,
            'warehouse_branch' => $this->branch,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'gender' => Gender::tryFrom($this->gender)->label(),
            'dob' => $this->dob,
            'working' => $this->working,
            'status' => WorkingStatus::tryFrom($this->working)->label(),
        ];
    }
}
