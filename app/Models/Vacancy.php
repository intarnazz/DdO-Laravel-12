<?php

namespace App\Models;

class Vacancy extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'price',
    ];
}
