<?php

namespace App\Models;

use App\Models\Concerns\HasManyStaff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    use HasManyStaff;

    protected $fillable = [
        'name',
    ];
}
