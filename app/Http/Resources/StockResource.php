<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'unit' => $category->unit,
                'description' => $category->description,
            ],
            'category_name' => $category->name,
            'category_unit' => $category->unit,
            'quantity' => $this->quantity,
            'import_id' => $this->import_id,
            'imported_date' => $import->created_at->format('H:i:s d/m/Y'),
            'import' => [
                'id' => $this->import_id,
                'created_by' => $staff->name,
                'provider' => $provider ? $provider->name : $import->warehouseBranch->name,
                'created_at' => $import->created_at->format('H:i:s d/m/Y')
            ],
            'expiry_date' => $this->expiry_date,
        ];
    }
}
