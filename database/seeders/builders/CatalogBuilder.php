<?php

namespace Database\Seeders\Builders;

use App\Models\Brand;
use App\Models\Characteristic;
use App\Models\Filter;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Type;
use App\Models\Variant;
use Faker\Factory;
use Faker\Generator;

/**
 * CatalogBuilder — фасад над всеми билдерами каталога.
 *
 * Новое:
 *   ->withColorsPerProduct(2)     — сколько цветов назначать каждому продукту
 *   ->withBrands($brands)         — передать уже созданные бренды (по 1 на изображение)
 */
class CatalogBuilder
{
    protected Generator $faker;

    // --- Type ---
    protected string $name = '';
    protected ?string $visibleName = null;
    protected ?string $description = null;
    protected ?int $selfId = null;

    // --- Images ---
    /** @var \App\Models\Image[] */
    protected array $images = [];
    protected bool $useSecondaryImage = false;   // ← новое свойство (по умолчанию 1 картинка)

    // --- Фильтр color ---
    protected bool $hasColorFilter = false;
    /** @var string[] */
    protected array $colorValues = [];

    // --- Сколько цветов на продукт ---
    protected int $colorsPerProduct = 1;

    // --- Фильтр brand ---
    protected bool $hasBrandFilter = false;
    /** @var Brand[] */
    protected array $brands = [];               // ← новые бренды из сеедера

    // --- Фильтр size ---
    protected bool $hasSizeFilter = false;
    /** @var string[] */
    protected array $sizeValues = [];

    // --- Генерируемые фильтры ---
    protected bool $hasGeneratedFilters = false;
    protected int $generatedFilterCharacteristicsCount = 10;

    // --- Теги / варианты ---
    protected bool $hasTags = false;
    protected bool $hasVariants = false;

    // --- Продукты ---
    protected int $productsPerIteration = 10;
    protected bool $withDefaultOptions = false;

    // -----------------------------------------------------------------------

    public static function make(): static
    {
        return new static();
    }

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    // -----------------------------------------------------------------------
    // Type
    // -----------------------------------------------------------------------

    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function visibleName(string $visibleName): static
    {
        $this->visibleName = $visibleName;
        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function selfId(int $selfId): static
    {
        $this->selfId = $selfId;
        return $this;
    }

    // -----------------------------------------------------------------------
    // Images
    // -----------------------------------------------------------------------

    public function withImages(array $images): static
    {
        $this->images = $images;
        return $this;
    }

    /**
     * Включить вторую картинку для продуктов каталога.
     * По умолчанию выключено (только 1 картинка — image_secondary_id = null).
     */
    public function withSecondaryImage(bool $use = false): static
    {
        $this->useSecondaryImage = $use;
        return $this;
    }

    // -----------------------------------------------------------------------
    // Фильтры
    // -----------------------------------------------------------------------

    public function withColorFilter(array $hexValues): static
    {
        $this->hasColorFilter = true;
        $this->colorValues = $hexValues;
        return $this;
    }

    /**
     * Сколько цветов назначать каждому продукту (1 или 2 для тестов).
     * По умолчанию 1 — как было раньше.
     */
    public function withColorsPerProduct(int $count = 1): static
    {
        $this->colorsPerProduct = max(1, $count);
        return $this;
    }

    public function withBrandFilter(): static
    {
        $this->hasBrandFilter = true;
        return $this;
    }

    /**
     * Передать уже созданные бренды (по 1 бренду на каждое изображение).
     * Если массив передан — генерация брендов внутри билдера отключается.
     *
     * @param Brand[] $brands
     */
    public function withBrands(array $brands): static
    {
        $this->brands = $brands;
        $this->hasBrandFilter = true;
        return $this;
    }

    public function withSizeFilter(array $sizeValues): static
    {
        $this->hasSizeFilter = true;
        $this->sizeValues = $sizeValues;
        return $this;
    }

    public function withGeneratedFilters(int $characteristicsPerFilter = 10): static
    {
        $this->hasGeneratedFilters = true;
        $this->generatedFilterCharacteristicsCount = $characteristicsPerFilter;
        return $this;
    }

    public function withTags(): static
    {
        $this->hasTags = true;
        return $this;
    }

    public function withVariants(): static
    {
        $this->hasVariants = true;
        return $this;
    }

    public function withProducts(int $perIteration = 10): static
    {
        $this->productsPerIteration = $perIteration;
        return $this;
    }

    public function withDefaultOptions(): static
    {
        $this->withDefaultOptions = true;
        return $this;
    }

    // -----------------------------------------------------------------------
    // Build
    // -----------------------------------------------------------------------

    public function build(): array
    {
        // ---- 1. Type -------------------------------------------------------
        $typeBuilder = TypeBuilder::make()->name($this->name);
        if ($this->visibleName !== null) $typeBuilder->visibleName($this->visibleName);
        if ($this->description !== null) $typeBuilder->description($this->description);
        if ($this->selfId !== null) $typeBuilder->selfId($this->selfId);
        $type = $typeBuilder->build()['type'];

        // ---- 2. Color filter -----------------------------------------------
        $colorFilter = null;
        $characteristicColors = [];

        if ($this->hasColorFilter) {
            $colorResult = FilterBuilder::make()
                ->name('color')
                ->type('color')
                ->linkToType($type->id)
                ->withCharacteristics(array_map(
                    fn(int $i, string $hex) => ['name' => $hex, 'image_id' => $i + 1],
                    array_keys($this->colorValues),
                    $this->colorValues
                ))
                ->build();

            $colorFilter = $colorResult['filter'];
            $characteristicColors = $colorResult['characteristics'];
        }

        // ---- 3. Brand filter -----------------------------------------------
        $brandFilter = null;
        if ($this->hasBrandFilter) {
            $brandFilter = FilterBuilder::make()
                ->name('brand')
                ->type('brand')
                ->linkToType($type->id)
                ->build()['filter'];
        }

        // ---- 4. Цикл по изображениям ---------------------------------------
        $products = [];
        $tags = [];
        $variants = [];
        $brands = $this->brands;          // если переданы — используем их
        $iterationFilters = [];

        $iterationIndex = 0;
        foreach ($this->images as $image) {

            $tag = $this->hasTags ? TypeBuilder::addTagTo($type->id) : null;
            if ($tag) $tags[] = $tag;

            $variant = $this->hasVariants ? TypeBuilder::addVariantTo($type->id) : null;
            if ($variant) $variants[] = $variant;

            // Бренд — либо из переданного массива, либо создаём автоматически
            $brand = null;
            if (count($this->brands) > 0) {
                $brand = $this->brands[$iterationIndex] ?? null;
            } elseif ($brandFilter !== null) {
                $text = TextBuilder::make()->build();
                $brand = BrandBuilder::make()
                    ->imageId($image->id)
                    ->textId($text->id)
                    ->linkToFilter($brandFilter->id)
                    ->build();
                $brands[] = $brand;
            }

            // Генерируемый фильтр
            if ($this->hasGeneratedFilters) {
                $iterationFilters[] = FilterBuilder::make()
                    ->linkToType($type->id)
                    ->withCharacteristics(array_map(
                        fn(int $j) => ['image_id' => $image->id, 'name' => $this->faker->word()],
                        range(0, $this->generatedFilterCharacteristicsCount - 1)
                    ))
                    ->build();
            }

            // Продукты
            for ($p = 0; $p < $this->productsPerIteration; $p++) {
                $productBuilder = ProductBuilder::make()
                    ->imagePrimaryId($image->id)
                    ->typeId($type->id)
                    ->isStock(true)
                    ->withGalleryImages($this->images);

                if ($this->useSecondaryImage) {
                    $productBuilder->imageSecondaryId($image->id);
                }

                if ($tag) $productBuilder->tagId($tag->id);
                if ($variant) $productBuilder->variantId($variant->id);
                if ($brand) $productBuilder->brandId($brand->id);

                $products[] = $productBuilder->build()['product'];
            }

            $iterationIndex++;
        }

        // ---- 5. Generated filters characteristics --------------------------
        if (!empty($iterationFilters)) {
            foreach ($products as $index => $product) {
                $groupIndex = intdiv($index, $this->productsPerIteration);

                ProductBuilder::attachCharacteristicsTo(
                    $product->id,
                    array_map(
                        fn(array $f) => $f['characteristics'][$groupIndex % count($f['characteristics'])]->id,
                        $iterationFilters
                    )
                );
            }
        }

        // ---- 6. Size filter ------------------------------------------------
        $sizeFilter = null;
        $characteristicSizes = [];

        if ($this->hasSizeFilter) {
            $sizeResult = FilterBuilder::make()
                ->name('size')
                ->type('size')
                ->linkToType($type->id)
                ->withCharacteristics(array_map(
                    fn(int $i, string $size) => ['name' => $size, 'image_id' => $i + 1],
                    array_keys($this->sizeValues),
                    $this->sizeValues
                ))
                ->build();

            $sizeFilter = $sizeResult['filter'];
            $characteristicSizes = $sizeResult['characteristics'];
        }

        // ---- 7. Color + size + options на каждый продукт -------------------
        $sizeCharIds = array_map(fn($c) => $c->id, $characteristicSizes);
        $colorCount = count($characteristicColors);
        $colorIndex = 0;

        foreach ($products as $product) {
            if ($this->withDefaultOptions) {
                OptionBuilder::attachDefaults($product);
            }

            $perProductCharIds = [];

            // === НОВОЕ: назначаем нужное количество цветов ===
            if ($colorCount > 0) {
                for ($c = 0; $c < $this->colorsPerProduct; $c++) {
                    $perProductCharIds[] = $characteristicColors[$colorIndex]->id;
                    $colorIndex = ($colorIndex + 1) % $colorCount;
                }
            }

            if (!empty($sizeCharIds)) {
                array_push($perProductCharIds, ...$sizeCharIds);
            }

            if (!empty($perProductCharIds)) {
                ProductBuilder::attachCharacteristicsTo($product->id, $perProductCharIds);
            }
        }

        return [
            'type' => $type,
            'products' => $products,
            'tags' => $tags,
            'variants' => $variants,
            'brands' => $brands,
            'filters' => [
                'color' => $colorFilter,
                'brand' => $brandFilter,
                'size' => $sizeFilter,
                'extra' => array_column($iterationFilters, 'filter'),
            ],
        ];
    }
}
