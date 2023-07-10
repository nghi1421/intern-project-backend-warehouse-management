<?php

namespace App\Models\Concerns;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyStaff
{
    public function staffs(): HasMany
    {
        return $this->hasMany(Staff::class);
    }
}
