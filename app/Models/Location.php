<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'name',
        'warehouse_branch_id',
    ];

    public function warehouseBranch(): BelongsTo
    {
        return $this->belongsTo(WarehouseBranch::class);
    }
}