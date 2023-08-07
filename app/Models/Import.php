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

                    $stocks = [];

                    foreach ($importDetail->categories as $detail) {

                        for ($i = 0; $i < $detail->pivot->quantity; $i++) {

                            $stocks[] = [
                                'category_id' => $detail->getKey(),
                                'import_id' => $import->getKey(),
                            ];
                        }
                    }

                    $status = (bool) Stock::query()->insertOrIgnore($stocks);

                    DB::commit();

                    return $status;
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
