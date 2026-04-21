<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filter extends BaseModel
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->hidden = array_merge($this->hidden, [
            'brands',
            'characteristics',
        ]);
    }

//    protected array $with_one_only = ['types'];

    public function content()
    {
        return $this->content = match ($this->type) {
            'brand' => $this->brands()->get(),
            default => $this->characteristics()->get(),
        };
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
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

