<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStaff;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Export extends Model
{
    use HasFactory;
    use BelongsToStaff;

    protected $fillable = [
        'staff_id',
        'status',
        'warehouse_branch_id',
        'to_warehouse_branch_id',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::updated(function (Export $export) {
            if ($export->status === 3) {
                try {
                    DB::beginTransaction();

                    $exportDetails = $export->categories;

                    $importIds = Import::query()
                        ->where('warehouse_branch_id', $export->warehouse_branch_id)
                        ->pluck('id');

                    foreach ($exportDetails as $detail) {
                        $categoryQuantity = $detail->pivot->quantity;

                        $stocks = Stock::query()
                            ->where('category_id', $detail->getKey())
                            ->whereIn('import_id', $importIds)
                            ->orderBy('expiry_date', 'asc')
                            ->get();

                        foreach ($stocks as $stock) {
                            if ($stock->quantity < $categoryQuantity) {
                                $categoryQuantity -= $stock->quantity;
                                $stock->delete();
                            } else {
                                $stock->update(['quantity' => $stock->quantity - $categoryQuantity]);
                                $categoryQuantity = 0;

                                break;
                            }
                        }

                        if ($categoryQuantity !== 0) {
                            throw new Exception('Warehouse branch does not enough stock.');
                        }
                    }

                    if ($export->to_warehouse_branch_id) {
                        $newImportDetails = [];

                        foreach ($export->categories->toArray() as $category) {
                            $newImportDetails[$category['id']] = [
                                'quantity' => $category['pivot']['quantity'],
                                'unit_price' => 0,
                            ];
                        }

                        $import = Import::query()->create([
                            'from_warehosue_branch_id' => $export->warehouse_branch_id,
                            'status' => 1,
                            'staff_id' => $export->staff_id,
                            'warehouse_branch_id' => $export->to_warehouse_branch_id,
                        ]);

                        $import->categories()->attach($newImportDetails);
                    }

                    DB::commit();
                } catch (Exception $exception) {
                    DB::rollback();
                    throw $exception;
                }
            }
        });

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
