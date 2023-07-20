<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStaff;
use App\Models\Enums\ImportStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Import extends Model
{
    use HasFactory;
    use BelongsToStaff;

    protected $fillable = [
        'provider_id',
        'staff_id',
        'status',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(WarehouseBranch::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($import) {
            $categories = $import->categories;

            if ($categories->isNotEmpty()) {

                $import->categories()->detach();
            }
        });
    }

    public function provider(): BelongsTo
    {
        return $this->belongsto(Provider::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'import_details')->withPivot(['quantity', 'unit_price']);
    }
}
