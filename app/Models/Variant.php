<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variant extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
    ];

    protected array $with_one_only = ['type'];


    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
