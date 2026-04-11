<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Characteristic extends BaseModel
{

    protected $with = ['image'];
    protected array $with_one_only = ['product'];

    public function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_characteristics', 'characteristic_id', 'product_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
