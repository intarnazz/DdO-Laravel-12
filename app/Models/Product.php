<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'filters',
        'images',
    ];

    #[\NoDiscard("The name must not be discarded")]
    protected static function filter($query, $filters)
    {
        if (isset($filters->type)) $query->where('type_id', $filters->type);
        if (isset($filters->tag)) $query->where('tag_id', $filters->tag);
        if (isset($filters->variant)) $query->where('variant_id', $filters->variant);
        if (isset($filters->brand)) $query->where('brand_id', $filters->brand);

        return $query;
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'galleries', 'product_id', 'image_id');
    }

    public function filters(): BelongsToMany
    {
        return $this->belongsToMany(Filter::class, 'product_filters', 'product_id', 'filter_id');
    }

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
