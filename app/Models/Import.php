<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStaff;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Import extends Model
{
    use HasFactory;
    use BelongsToStaff;

    protected $fillable = [
        'provider_id',
        'warehouse_branch_id',
        'from_warehouse_branch_id',
        'staff_id',
        'status',
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function (Import $import) {
            if ($import->status === 3) {
                try {
                    DB::beginTransaction();

                    $importDetail = $import->load('categories');

                    foreach ($importDetail->categories as $detail) {

                        Stock::query()->create([
                            'import_id' => $import->getKey(),
                            'category_id' => $detail->getKey(),
                            'quantity' => $detail->pivot->quantity
                        ]);
                    }

                    DB::commit();
                } catch (Exception $exception) {
                    DB::rollback();

                    throw $exception;
                }
            }
        });

        static::deleting(function ($import) {
            $categories = $import->categories;

            if ($categories->isNotEmpty()) {

                $import->categories()->detach();
            }
        });
    }

    public function warehouseBranch(): BelongsTo
    {
        return $this->belongsTo(WarehouseBranch::class);
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
