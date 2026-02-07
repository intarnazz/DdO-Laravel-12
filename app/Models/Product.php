<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Product model representing a product entity in the database.
 *
 * This model extends BaseModel and defines relationships, fillable attributes,
 * and custom filtering logic for products.
 *
 * @property string $name The name of the product.
 * @property string $description The description of the product.
 * @property string|null $video_url The URL to a video related to the product.
 * @property float $price The price of the product.
 * @property string $article The article or SKU of the product.
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
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'video_url',
        'price',
        'article',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array<int, string>
     */
    protected $with = [
        'image',
    ];

    /**
     * Relations to load only once.
     *
     * @var array<int, string>
     */
    protected array $with_one_only = [
        'type',
        'tag',
        'variant',
        'brand',
        'images',
        'characteristics',
    ];

    /**
     * Sortable attributes for the model.
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

    /**
     * Retrieves metadata about the minimum and maximum price values.
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
    protected static function _filter($query, $filters)
    {
        foreach ($filters as $key => $filter) {
            if ($filter === null || $filter === '') {
                continue;
            }

            if ($key === 'type') $query->where('type_id', $filter);
            elseif ($key === 'tag') $query->where('tag_id', $filter);
            elseif ($key === 'variant') $query->where('variant_id', $filter);
            elseif ($key === 'brand') $query->whereIn('brand_id', array_filter(array_map('trim', explode(',', $filter))));
            else {
                $query->whereHas('characteristics', function ($q) use ($filter) {
                    $q->whereIn('characteristics.id', array_filter(array_map('trim', explode(',', $filter))));
                });
            }
        }
        return $query;
    }

    /**
     * Defines the many-to-many relationship with characteristics.
     *
     * @return BelongsToMany
     */
    public function characteristics(): BelongsToMany
    {
        return $this->belongsToMany(Characteristic::class, 'product_characteristics', 'product_id', 'characteristic_id');
    }

    /**
     * Defines the many-to-many relationship with images.
     *
     * @return BelongsToMany
     */
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'galleries', 'product_id', 'image_id');
    }

    /**
     * Defines the belongs-to relationship with type.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Defines the belongs-to relationship with tag.
     *
     * @return BelongsTo
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Defines the belongs-to relationship with variant.
     *
     * @return BelongsTo
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * Defines the belongs-to relationship with brand.
     *
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Defines the belongs-to relationship with image.
     *
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
