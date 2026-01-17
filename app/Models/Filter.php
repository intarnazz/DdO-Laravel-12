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
}
