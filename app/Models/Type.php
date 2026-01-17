<?php

namespace App\Models;

class Type extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
    ];
    public static bool $isTree = true;
}
