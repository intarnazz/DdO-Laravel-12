<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

class Type extends BaseModel
{
    protected $fillable = [
        'name',
        'visible_name',
        'description',
    ];

    protected array $with_one_only = ['filters.content', 'tags'];
    protected static bool $isTree = true;

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function filters()
    {
        return $this->belongsToMany(Filter::class, 'type_filters', 'type_id', 'filter_id');
    }
}
