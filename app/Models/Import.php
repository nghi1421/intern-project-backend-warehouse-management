<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Import extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'user_id',
        'status',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($import) {
            $categories = $import->categories;

            if ($categories->isNotEmpty()) {

                $import->categories()->detach();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsto(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsto(Customer::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'import_details')->withPivot(['quantity', 'unit_price']);
    }
}