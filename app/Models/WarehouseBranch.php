<?php

namespace App\Models;

use App\Models\Concerns\HasManyStaff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'address',
        'opening',
        'phone_number',
    ];

    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
    }

    public function exports(): HasMany
    {
        return $this->hasMany(Export::class);
    }
}
