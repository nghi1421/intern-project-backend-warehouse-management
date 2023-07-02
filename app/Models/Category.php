<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'unit',
        'description'
    ];

    public function conservations(): HasMany
    {
        return $this->hasMany(Conservation::class);
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }
}