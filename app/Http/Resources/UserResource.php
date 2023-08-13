<?php

namespace App\Http\Resources;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $staff = Staff::query()->where('user_id', $this->getKey())->first();
        return  [
            'id' => $this->getKey(),
            'username' => $this->username,
            'staff_name' => $staff->name ?? '_',
            'role_id' => $this->role_id,
            'role_name' => $this->role->name,
            'created_at' => $this->created_at->format('H:i:s d/m/Y'),
            'updated_at' => $this->updated_at->format('H:i:s d/m/Y'),
        ];
    }
}