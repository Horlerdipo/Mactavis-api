<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'status',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
}
