<?php

namespace App\Models;

use App\Models\Enums\ExportStatus;
use App\Models\Enums\CauseExport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Export extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'user_id',
        'status',
        'warehouse_branch_id',
        'cause'
    ];

    protected $casts = [
        'status' => ExportStatus::class,
        'cause' => CauseExport::class,
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(WarehouseBranch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'export_details')->withPivot(['quantity']);
    }
}
