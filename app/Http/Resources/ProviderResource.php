<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'created_at' => $this->created_at->format('H:i:s d/m/Y'),
            'updated_at' => $this->updated_at->format('H:i:s d/m/Y'),
        ];
    }
}