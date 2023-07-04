<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function principles(): BelongsToMany
    {
        return $this->belongsToMany(Principle::class, 'category_principles');
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }
}
