<?php

namespace App\Http\Resources;

use App\Models\Enums\ImportStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $categories = array_map(function ($category) {
            $quantity = $category['pivot']['quantity'];
            $unitPrice = $category['pivot']['unit_price'];
            unset($category['pivot']);
            return [
                ...$category,
                'amount' => $quantity,
                'unit_price' => $unitPrice
            ];
        }, $this->categories()->select(['id', 'name', 'unit'])->get()->toArray());

        return [
            'id' => $this->id,
            'staff' => $this->staff,
            'staff_name' => $this->staff->name,
            'provider_name' => $this->provider->name,
            'warehouse_branch_id' => $this->warehouse_branch_id,
            'warehouse_branch_name' => $this->warehouseBranch->name,
            'categories' => $categories,
            'provider' => $this->provider,
            'status' => ImportStatus::tryFrom($this->status)->label(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
