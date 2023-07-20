<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
            'unit' => $this->unit,
            'principles' => $this->principles,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
