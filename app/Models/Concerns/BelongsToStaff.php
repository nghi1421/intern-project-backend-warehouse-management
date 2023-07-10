<?php

namespace App\Models\Concerns;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToStaff
{
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
