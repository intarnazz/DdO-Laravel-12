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
        'characteristics',
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

                $f = Filter::where('type', $key)->first();

                $query->whereHas('characteristics', function ($q) use ($f, $filter) {
                    $q->where('filter_id', $f->id)
                        ->whereIn('id', array_filter(array_map('trim', explode(',', $filter))));
                });
            }
        }
        return $query;
    }

    public function characteristics(): HasMany
    {
        return $this->hasMany(Characteristic::class);
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
