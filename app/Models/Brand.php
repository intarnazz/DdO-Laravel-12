<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Brand extends BaseModel
{
    protected $with = ['image'];

    /**
     * Сортируемые атрибуты модели.
     *
     * @var array<int, string>
     */
    protected static array $sortable = [
        'id',
    ];

    protected array $with_one_only = ['text', 'filter.types'];

    protected static function booted()
    {
        static::deleting(function ($brand) {
            $brand->text()->delete();
            $brand->image()->delete();
        });
    }

    public function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class);
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
