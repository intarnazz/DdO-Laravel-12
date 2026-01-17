<?php

namespace App\Models;

class Documentation extends BaseModel
{
    public array $with_one_only = ['text'];
    public static bool $isTree = true;

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
