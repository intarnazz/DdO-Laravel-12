<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends BaseModel
{

    protected array $with_one_only = ['type'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
