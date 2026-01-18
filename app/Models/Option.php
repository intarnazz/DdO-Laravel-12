<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'price',
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
