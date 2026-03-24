<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

/**
 * Модель Product, представляющая сущность продукта в базе данных.
 *
 * Эта модель расширяет BaseModel и определяет отношения, заполняемые атрибуты,
 * а также логику кастомной фильтрации для продуктов.
 *
 * @property string $name Название продукта.
 * @property string $description Описание продукта.
 * @property string|null $video_url URL видео, связанного с продуктом.
 * @property float $price Цена продукта.
 * @property string $article Артикул или SKU продукта.
 * @property-read Collection|Characteristic[] $characteristics
 * @property-read Collection|Image[] $images
 * @property-read Type|null $type
 * @property-read Tag|null $tag
 * @property-read Variant|null $variant
 * @property-read Brand|null $brand
 * @property-read Image|null $image
 */
class Product extends BaseModel
{
    use Searchable;

    /**
     * Атрибуты, которые могут быть массово присвоены.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'video_url',
        'price',
        'article',
        'discount',
    ];

    /**
     * Отношения, которые загружаются жадно при каждом запросе.
     *
     * @var array<int, string>
     */
    protected $with = [
        'image_primary',
        'image_secondary',
        'variant',
    ];

    /**
     * Отношения, которые загружаются только один раз.
     *
     * @var array<int, string>
     */
    protected array $with_one_only = [
        'color',
        'variant.products.color',
        'type',
        'tag',
        'brand',
        'images',
        'characteristics.filter',
    ];

    /**
     * Сортируемые атрибуты модели.
     *
     * @var array<int, string>
     */
    protected static array $sortable = [
        'variant_id',
        'updated_at',
        'price',
        'views',
        'discount',
    ];

    public function color(): BelongsToMany
    {
        return $this->BelongsToMany(Characteristic::class, 'product_characteristics', 'product_id', 'characteristic_id')
            ->whereHas('filter', function (Builder $query) {
                $query->where('type', 'color');
            });
    }

    protected function _after_get()
    {
        return $this->increment('views');
    }

    /**
     * Получает метаданные о минимальном и максимальном значениях цены.
     *
     * @return array<string, array<string, float|null>>
     */
    protected static function _meta()
    {
        return [
            'price' => [
                'min' => self::min('price'),
                'max' => self::max('price'),
            ]
        ];
    }

    #[\NoDiscard("The name must not be discarded")]
    protected static function _filter($query, $request)
    {
        foreach ($request->query() as $key => $value) {

            match (true) {
                $key === 'type' => $query->where('type_id', $value),
                $key === 'tag' => $query->where('tag_id', $value),
                $key === 'variant' => $query->where('variant_id', $value),
                $key === 'brand' => $query->whereIn('brand_id', static::stringToArray($value)),
                $key === 'price-max' => $query->where('price', '<=', $value),
                Filter::where('name', $key)->exists() => $query->whereHas('characteristics', function ($q) use ($value) {
                    $q->whereIn('characteristics.id', static::stringToArray($value));
                }),
                default => null
            };
        }
        return $query;
    }

    /**
     * Применяет фильтрацию поиска и сортировку по релевантности с использованием Laravel Scout.
     *
     * Этот метод принимает конструктор запроса Eloquent и строку поиска,
     * использует Scout для поиска соответствующих ID, отсортированных по релевантности,
     * и модифицирует запрос для фильтрации и сортировки соответственно.
     *
     * @param Builder $query Конструктор запроса Eloquent для модификации.
     * @param string $search Строка поискового запроса.
     * @return Builder Модифицированный конструктор запроса.
     */
    #[\NoDiscard("The name must not be discarded")]
    protected static function _search(Builder $query, string $search): Builder
    {
        $ids = self::search($search)->keys();

        if (empty($ids)) return $query;

        return $query->whereIn('id', $ids);
    }

    /**
     * Получает индексируемый массив данных для модели.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return $this->only([
            'name',
            'article',
        ]);
    }

    /**
     * Определяет отношение многие-ко-многим с характеристиками.
     *
     * @return BelongsToMany
     */
    public function characteristics(): BelongsToMany
    {
        return $this->belongsToMany(Characteristic::class, 'product_characteristics', 'product_id', 'characteristic_id');
    }

    /**
     * Определяет отношение многие-ко-многим с изображениями.
     *
     * @return BelongsToMany
     */
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'galleries', 'product_id', 'image_id');
    }

    /**
     * Определяет отношение принадлежит-к с типом.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Определяет отношение принадлежит-к с тегом.
     *
     * @return BelongsTo
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Определяет отношение принадлежит-к с вариантом.
     *
     * @return BelongsTo
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * Определяет отношение принадлежит-к с брендом.
     *
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Определяет отношение принадлежит-к с изображением.
     *
     * @return BelongsTo
     */
    public function image_primary(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'image_primary_id');
    }

    public function image_secondary(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'image_secondary_id');
    }
}
