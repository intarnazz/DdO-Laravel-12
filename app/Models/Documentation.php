<?php

namespace App\Models;

class Documentation extends BaseModel
{
    public $with_one_only = ['text'];

    protected static function booted()
    {
        static::deleting(function ($brand) {
            $brand->text()->delete();
        });
    }

    public function text()
    {
        return $this->belongsTo(Text::class);
    }
}
