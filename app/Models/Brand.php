<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'type'
    ];
    protected $with = ['image'];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
