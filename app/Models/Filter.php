<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Filter extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'group',
        'value',
        'value_type',
        'is_visible',
    ];

    protected array $with_one_only = ['types', 'products'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_filters', 'filter_id', 'product_id');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'type_filters', 'filter_id', 'type_id');
    }

}
