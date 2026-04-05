<?php

namespace Database\Seeders\Builders;

use App\Models\Gallery;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductCharacteristic;
use Faker\Factory;
use Faker\Generator;

/**
 * Builder for Product (+ Gallery + Options + ProductCharacteristics).
 *
 * Usage:
 *   $result = ProductBuilder::make()
 *       ->imagePrimaryId(1)
 *       ->imageSecondaryId(2)
 *       ->typeId($type->id)
 *       ->tagId($tag->id)
 *       ->variantId($variant->id)
 *       ->brandId($brand->id)
 *       ->name('Torex Ultimatum / Дуб беленый')
 *       ->description('...')
 *       ->videoUrl('https://...')
 *       ->price(45000)
 *       ->article('TOREX-ULT-001')
 *       ->discount(10)
 *       ->isStock(true)
 *       ->isInStore(false)
 *       ->withGalleryImages($images)          // Image[]
 *       ->withCharacteristic($colorChar->id)  // single characteristic
 *       ->withCharacteristics($sizeCharIds)   // multiple characteristic IDs
 *       ->withDefaultOptions()                // коробка + наличник + 2 random
 *       ->build();
 *
 *   $product = $result['product'];  // Product
 */
class ProductBuilder
{
    protected Generator $faker;

    protected ?int    $imagePrimaryId   = null;
    protected ?int    $imageSecondaryId = null;
    protected ?int    $typeId           = null;
    protected ?int    $tagId            = null;
    protected ?int    $variantId        = null;
    protected ?int    $brandId          = null;
    protected ?string $name             = null;
    protected ?string $description      = null;
    protected ?string $videoUrl         = null;
    protected ?int    $price            = null;
    protected ?string $article          = null;
    protected ?int     $discount        = null;
    protected bool    $isStock          = true;
    protected ?bool   $isInStore        = null;

    /** @var Image[] */
    protected array $galleryImages = [];

    /** @var int[] */
    protected array $characteristicIds = [];

    protected bool $defaultOptions = false;

    public static function make(): static
    {
        return new static();
    }

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    // -----------------------------------------------------------------------
    // Product fields
    // -----------------------------------------------------------------------

    public function imagePrimaryId(int $id): static
    {
        $this->imagePrimaryId = $id;
        return $this;
    }

    public function imageSecondaryId(int $id): static
    {
        $this->imageSecondaryId = $id;
        return $this;
    }

    public function typeId(int $typeId): static
    {
        $this->typeId = $typeId;
        return $this;
    }

    public function tagId(int $tagId): static
    {
        $this->tagId = $tagId;
        return $this;
    }

    public function variantId(int $variantId): static
    {
        $this->variantId = $variantId;
        return $this;
    }

    public function brandId(int $brandId): static
    {
        $this->brandId = $brandId;
        return $this;
    }

    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function videoUrl(string $url): static
    {
        $this->videoUrl = $url;
        return $this;
    }

    public function price(int $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function article(string $article): static
    {
        $this->article = $article;
        return $this;
    }

    /**
     * Discount percentage (0–100). 0 means no discount.
     */
    public function discount(int $discount): static
    {
        $this->discount = $discount;
        return $this;
    }

    public function isStock(bool $isStock): static
    {
        $this->isStock = $isStock;
        return $this;
    }

    public function isInStore(bool $isInStore): static
    {
        $this->isInStore = $isInStore;
        return $this;
    }

    // -----------------------------------------------------------------------
    // Gallery
    // -----------------------------------------------------------------------

    /**
     * @param Image[] $images
     */
    public function withGalleryImages(array $images): static
    {
        $this->galleryImages = $images;
        return $this;
    }

    // -----------------------------------------------------------------------
    // Characteristics
    // -----------------------------------------------------------------------

    public function withCharacteristic(int $characteristicId): static
    {
        $this->characteristicIds[] = $characteristicId;
        return $this;
    }

    /**
     * @param int[] $characteristicIds
     */
    public function withCharacteristics(array $characteristicIds): static
    {
        foreach ($characteristicIds as $id) {
            $this->characteristicIds[] = $id;
        }
        return $this;
    }

    // -----------------------------------------------------------------------
    // Options
    // -----------------------------------------------------------------------

    /**
     * Attach the standard option set: коробка + наличник + 2 random.
     */
    public function withDefaultOptions(): static
    {
        $this->defaultOptions = true;
        return $this;
    }

    // -----------------------------------------------------------------------
    // Build
    // -----------------------------------------------------------------------

    /**
     * @return array{
     *     product: Product,
     * }
     */
    public function build(): array
    {
        $product = Product::create([
            'image_primary_id'   => $this->imagePrimaryId   ?? 1,
            'image_secondary_id' => $this->imageSecondaryId ?? null,   // ← изменено: теперь по умолчанию null (1 картинка)
            'type_id'            => $this->typeId           ?? 1,
            'tag_id'             => $this->tagId            ?? null,
            'variant_id'         => $this->variantId        ?? null,
            'brand_id'           => $this->brandId          ?? null,
            'name'               => $this->name             ?? $this->faker->text(16) . ' / ' . $this->faker->text(16),
            'description'        => $this->description      ?? $this->faker->text(255),
            'video_url'          => $this->videoUrl         ?? $this->faker->url(),
            'price'              => $this->price            ?? $this->faker->biasedNumberBetween(10000, 50000),
            'article'            => $this->article          ?? $this->faker->word() . '-' . $this->faker->word(),
            'discount'           => $this->discount         ?? $this->faker->boolean(33) ? $this->faker->biasedNumberBetween(0, 100) : 0,
            'is_stock'           => $this->isStock,
            'is_in_store'        => $this->isInStore        ?? $this->faker->boolean(),
        ]);

        // Gallery, Characteristics, Options — без изменений
        foreach ($this->galleryImages as $image) {
            Gallery::create([
                'image_id'   => $image->id,
                'product_id' => $product->id,
            ]);
        }

        foreach ($this->characteristicIds as $charId) {
            ProductCharacteristic::create([
                'product_id'        => $product->id,
                'characteristic_id' => $charId,
            ]);
        }

        if ($this->defaultOptions) {
            OptionBuilder::attachDefaults($product);
        }

        return [
            'product' => $product,
        ];
    }

    /**
     * Attaches characteristics to an already-existing product.
     * Use this when you need to add characteristics post-build
     * without creating a new product.
     *
     * @param int   $productId
     * @param int[] $characteristicIds
     */
    public static function attachCharacteristicsTo(int $productId, array $characteristicIds): void
    {
        foreach ($characteristicIds as $charId) {
            ProductCharacteristic::create([
                'product_id'        => $productId,
                'characteristic_id' => $charId,
            ]);
        }
    }
}
