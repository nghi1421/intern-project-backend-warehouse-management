<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $category = $this->category;

        $import = $this->import;

        $provider = $import->provider;

        $staff = $import->staff;

        return [
            'id' => $this->getKey(),
            'category' => $category,
            'category_name' => $category->name,
            'category_unit' => $category->unit,
            'unit' => $category->unit,
            'location_id' => $this->location_id,
            'location_name' => $this->location?->name,
            'location' => $this->location,
            'import_id' => $this->import_id,
            'import' => [
                'id' => $this->import_id,
                'created_by' => $staff->name,
                'provider' => $provider->name,
                'created_at' => $import->created_at->format('H:i:s d/m/Y')
            ],
            'expiry_date' => $this->expiry_date,
        ];
    }
}
