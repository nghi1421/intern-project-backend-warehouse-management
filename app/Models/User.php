<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Enums\Gender;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'phone_number',
        'avatar',
        'address',
        'gender',
        'position_id',
        'warehouse_branch_id',
        'dob',
        'working',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'gender' => Gender::class,
        'working' => 'boolean',
        'password' => 'hashed',
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

    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'user_actions');
    }
}
