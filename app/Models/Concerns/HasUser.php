<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasUser
{
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
