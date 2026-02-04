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

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->hidden = array_merge($this->hidden, [
            'brands',
            'characteristics',
        ]);

    }

//    protected array $with_one_only = ['types'];

    protected $appends = ['content'];

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

