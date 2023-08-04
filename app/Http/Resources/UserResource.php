<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return  [
            'id' => $this->getKey(),
            'username' => $this->username,
            'role' => $this->role,
            'role_name' => $this->role->name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'upddated_at' => $this->upddated_at->format('Y-m-d H:i:s'),
        ];
    }
}