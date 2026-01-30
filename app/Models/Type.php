<?php

namespace App\Models;

class Type extends BaseModel
{
    protected $fillable = [
        'name',
        'visible_name',
        'description',
    ];

    protected array $with_one_only = ['filters'];
    protected static bool $isTree = true;

    public function filters()
    {
        return $this->belongsToMany(Filter::class, 'type_filters', 'type_id', 'filter_id');
    }
}
