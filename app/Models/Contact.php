<?php

namespace App\Models;


class Contact extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'link',
        'type',
    ];
}
