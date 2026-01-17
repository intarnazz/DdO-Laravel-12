<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Characteristic extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_chosen'
    ];

    protected static function booted()
    {
        static::deleting(function ($brand) {
            $brand->image()->delete();
        });
    }

    public function image()
    {
        return $this->belongsTo(Image::class);

    }
}
