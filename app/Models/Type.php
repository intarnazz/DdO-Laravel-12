<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

class Type extends BaseModel
{
    protected array $with_one_only = ['filters', 'tags'];
    protected static bool $isTree = true;

    /**
     * Возвращает коллекцию данных ответа.
     *
     * @param Model | Collection $data Коллекция данных для обработки
     * @return Model | Collection Исходная коллекция данных
     */
    #[\NoDiscard("The name must not be discarded")]
    protected static function _response_data(Model|Collection $data): Model|Collection
    {
        if (!empty($data['filters'])) foreach ($data['filters'] as $filter) {
            $filter->content();
        }
        return $data;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function type(): belongsTo
    {
        return $this->belongsTo(Type::class, 'self_id');
    }

    public function types(): hasMany
    {
        return $this->hasMany(Type::class, 'self_id');
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function filters(): BelongsToMany
    {
        return $this->belongsToMany(Filter::class, 'type_filters', 'type_id', 'filter_id');
    }
}
