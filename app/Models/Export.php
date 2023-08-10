<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStaff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Export extends Model
{
    use HasFactory;
    use BelongsToStaff;

    protected $fillable = [
        'staff_id',
        'status',
        'warehouse_branch_id',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function ($export) {
            $categories = $export->categories;

            if ($categories->isNotEmpty()) {

                $export->categories()->detach();
            }
        });
    }

    public function warehouseBranch(): BelongsTo
    {
        return $this->belongsTo(WarehouseBranch::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'export_details')->withPivot(['quantity']);
    }
}
