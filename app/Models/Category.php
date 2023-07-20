<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'unit',
        'description'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Category $category) {
            $principles = $category->principles;

            if ($principles->isNotEmpty()) {

                $category->principles()->detach();
            }
        });
    }

    public function principles(): BelongsToMany
    {
        return $this->belongsToMany(Principle::class, 'category_principles');
    }

    public function imports(): BelongsToMany
    {
        return $this->belongsToMany(Import::class, 'import_details')->withPivot(['quantity', 'unit_price']);
    }

    public function exports(): BelongsToMany
    {
        return $this->belongsToMany(Export::class, 'export_details')->withPivot(['quantity']);
    }
}
