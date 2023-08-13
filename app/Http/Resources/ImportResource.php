<?php

namespace App\Http\Resources;

use App\Models\Enums\ImportStatus;
use App\Models\WarehouseBranch;
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

        $warehouseBranch = WarehouseBranch::query()->findOrFail($this->warehouse_branch_id);

        return [
            'id' => $this->id,
            'staff' => $this->staff,
            'staff_name' => $this->staff->name,
            'provided_from' => $this->provider_id
                ? $this->provider->name
                : $this->fromWarehouseBranch->name,
            'provider' => $this->provider_id
                ? $this->provider
                : $this->fromWarehouseBranch,
            'warehouse_branch_id' => $this->warehouse_branch_id,
            'warehouse_branch_name' => $warehouseBranch->name,
            'from_warehouse_branch_id' => $this->from_warehouse_branch_id,
            'categories' => $categories,
            'status_id' => $this->status,
            'status' => ImportStatus::tryFrom($this->status)->label(),
            'created_at' => $this->created_at->format('H:i:s d/m/Y'),
            'updated_at' => $this->updated_at->format('H:i:s d/m/Y'),
        ];
    }
}
