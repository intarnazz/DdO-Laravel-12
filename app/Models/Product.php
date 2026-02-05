<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'images',
        'characteristics',
    ];

    protected static array $sortable = [
        'variant_id'
    ];


    #[\NoDiscard("The name must not be discarded")]
    protected static function _filter($query, $filters)
    {
        foreach ($filters as $key => $filter) {
            if ($filter === null || $filter === '') {
                continue;
            }

            if ($key === 'type') $query->where('type_id', $filter);
            elseif ($key === 'tag') $query->where('tag_id', $filter);
            elseif ($key === 'variant') $query->where('variant_id', $filter);
            elseif ($key === 'brand') $query->whereIn('brand_id', array_filter(array_map('trim', explode(',', $filter))));
            else {
                $query->whereHas('characteristics', function ($q) use ($filter) {
                    $q->whereIn('characteristics.id', array_filter(array_map('trim', explode(',', $filter))));
                });
            }
        }
        return $query;
    }

    public function characteristics(): BelongsToMany
    {
        return $this->belongsToMany(Characteristic::class, 'product_characteristics', 'product_id', 'characteristic_id');
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'galleries', 'product_id', 'image_id');
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
