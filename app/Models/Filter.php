<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Filter extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'type',
    ];

    public function content(): BelongsToMany|hasMany
    {
        return match ($this->type) {
            'brand' => $this->belongsToMany(Brand::class, 'filter_brands', 'filter_id', 'brand_id'),
            default => $this->hasMany(Characteristic::class),
        };
    }


    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'filter_brands', 'filter_id', 'brand_id');
    }

    public function characteristics(): HasMany
    {
        return $this->hasMany(Characteristic::class);
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class, 'type_filters', 'filter_id', 'type_id');
    }
}

