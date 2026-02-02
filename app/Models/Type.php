<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends BaseModel
{
    protected $fillable = [
        'name',
        'visible_name',
        'description',
    ];

    protected array $with_one_only = ['filters', 'tags'];
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
