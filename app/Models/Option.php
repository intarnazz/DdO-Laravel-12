<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_set',
        'default_count',
    ];

    protected $with = ['image'];
    protected array $with_one_only = ['product'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
