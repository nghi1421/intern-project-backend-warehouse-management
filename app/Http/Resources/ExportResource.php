<?php

namespace App\Http\Resources;

use App\Models\Enums\ExportStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $categories = array_map(function ($category) {
            $quantity = $category['pivot']['quantity'];
            unset($category['pivot']);
            return [
                ...$category,
                'amount' => $quantity,
            ];
        }, $this->categories()->select(['id', 'name', 'unit'])->get()->toArray());

        return [
            'id' => $this->id,
            'staff' => $this->staff,
            'staff_name' => $this->staff->name,
            'warehouse_branch_id' => $this->warehouse_branch_id,
            'warehouse_branch_name' => $this->warehouseBranch->name,
            'categories' => $categories,
            'status_id' => $this->status,
            'status' => ExportStatus::tryFrom($this->status)->label(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
