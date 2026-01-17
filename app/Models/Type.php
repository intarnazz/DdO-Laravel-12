<?php

namespace App\Models;

class Type extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
    ];

    public array $with_one_only = ['filters'];
    public static bool $isTree = true;

    public function filters()
    {
        return $this->belongsToMany(Filter::class, 'type_filters', 'type_id', 'filter_id');
    }
}
