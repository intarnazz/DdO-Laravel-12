<?php

namespace App\Models;

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

    protected array $with_one_only = ['types'];

    public function types()
    {
        return $this->belongsToMany(Type::class, 'type_filters', 'filter_id', 'type_id');
    }

}
