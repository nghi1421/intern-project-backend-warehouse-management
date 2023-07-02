<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'shelf_name',
        'block_name',
    ];

    public function goods(): HasMany
    {
        return $this->hasMany(Goods::class);
    }

    public function numberOfGoods(): Attribute
    {
        return Attribute::make(
            get: function (): array {
                return $this->withCount('goods');
            }
        );
    }
}