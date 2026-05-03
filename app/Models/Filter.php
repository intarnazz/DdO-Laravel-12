<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filter extends BaseModel
{

//    public function __construct(array $attributes = [])
//    {
//        parent::__construct($attributes);
//
//        $this->hidden = array_merge($this->hidden, [
////            'brands',
//            'characteristics',
//        ]);
//    }

//    protected array $with_one_only = ['types'];

    public function content(string $type_id)
    {
        return $this->content = match ($this->type) {
            'brand' => Brand::whereIn(
                'id', Product::where('type_id', $type_id)
                ->distinct()->pluck('brand_id')->toArray()
            )->get(),
            'color' => Characteristic::whereHas('products', function ($query) use ($type_id) {
                $query
                    ->where('type_id', $type_id)
                    ->where('filter_id', $this->id);
            })->get(),
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

