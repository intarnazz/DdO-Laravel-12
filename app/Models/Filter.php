<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filter extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'type',
    ];

    protected $hidden = [
        'brands',
        'characteristics',
    ];

//    protected array $with_one_only = ['types', 'products'];

    protected static bool $loading_content = true;

    protected $appends = ['default_content'];

    protected function content(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->type) {
                    'brand' => $this->brands()->get(),
                    default => $this->characteristics()->get(),
                };
            }
        );
    }

    protected function defaultContent(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->type) {
                    'brand' => $this->brands()
                        ->offset(0)
                        ->limit(4)
                        ->get(),
                    default => $this->characteristics()
                        ->offset(0)
                        ->limit(4)
                        ->get(),
                };
            }
        );
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'filter_brands', 'filter_id', 'brand_id');
    }

    public function characteristics(): HasMany
    {
        return $this->hasMany(Characteristic::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_filters', 'filter_id', 'product_id');
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class, 'type_filters', 'filter_id', 'type_id');
    }
}

