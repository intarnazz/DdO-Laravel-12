<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
    ];

    protected array $with_one_only = ['type'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
