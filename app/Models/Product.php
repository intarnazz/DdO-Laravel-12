<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'video_url',
        'price',
        'article',
    ];

    protected $with = [
        'image',
    ];

    protected array $with_one_only = [
        'type',
        'tag',
        'variant',
        'brand',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

}
