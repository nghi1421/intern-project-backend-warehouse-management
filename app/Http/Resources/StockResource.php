<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $category = $this->category;

        return [
            'id' => $this->getKey(),
            'category_name' => $category->name,
            'category_unit' => $category->unit,
            'unit' => $category->unit,
            'location_id' => $this->location_id,
            'location_name' => $this->location?->name,
            'location' => $this->location,
            'import_id' => $this->import_id,
            'expiry_date' => $this->expiry_date,
        ];
    }
}
