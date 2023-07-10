<?php

namespace App\Models;

use App\Models\Concerns\HasUser;
use App\Models\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    use HasFactory;
    use HasUser;

    protected $fillable = [
        'name',
        'phone_number',
        'avatar',
        'address',
        'gender',
        'position_id',
        'user_id',
        'warehouse_branch_id',
        'dob',
        'working',
    ];

    protected $casts = [
        'gender' => Gender::class,
        'working' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(WarehouseBranch::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
    }

    public function exports(): HasMany
    {
        return $this->hasMany(Export::class);
    }
}
