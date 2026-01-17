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

    public array $with_one_only = ['text'];

    protected static function booted()
    {
        static::deleting(function ($brand) {
            $brand->text()->delete();
            $brand->image()->delete();
        });
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function text()
    {
        return $this->belongsTo(Text::class);
    }
}
