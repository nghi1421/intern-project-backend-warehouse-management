<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStaff;
use App\Models\Enums\ExportStatus;
use App\Models\Enums\CauseExport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Export extends Model
{
    use HasFactory;
    use BelongsToStaff;

    protected $fillable = [
        'user_id',
        'status',
        'warehouse_branch_id',
        'cause'
    ];

    protected $casts = [
        'status' => ExportStatus::class,
        'cause' => CauseExport::class,
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'export_details')->withPivot(['quantity']);
    }
}
