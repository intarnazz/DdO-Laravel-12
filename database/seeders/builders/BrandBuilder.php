<?php

namespace Database\Seeders\Builders;

use App\Models\Brand;
use App\Models\FilterBrand;
use Faker\Factory;
use Faker\Generator;

/**
 * Builder for Brand (+ optional FilterBrand links).
 *
 * Usage:
 *   $brand = BrandBuilder::make()
 *       ->imageId(1)
 *       ->textId(1)
 *       ->name('Torex')
 *       ->description('Российский производитель...')
 *       ->type('manufacturer')
 *       ->linkToFilter(42)       // links FilterBrand to this filter_id
 *       ->build();
 *
 *   // Multiple filter links:
 *   BrandBuilder::make()->name('Bravo')->linkToFilter(1)->linkToFilter(2)->build();
 */
class BrandBuilder
{
    protected Generator $faker;

    protected ?int    $imageId     = null;
    protected ?int    $textId      = null;
    protected ?string $name        = null;
    protected ?string $description = null;
    protected ?string $type        = null;

    /** @var int[] */
    protected array $filterIds = [];

    public static function make(): static
    {
        return new static();
    }

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function imageId(int $imageId): static
    {
        $this->imageId = $imageId;
        return $this;
    }

    public function textId(int $textId): static
    {
        $this->textId = $textId;
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

    public function type(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Links the created Brand to the given filter via FilterBrand.
     */
    public function linkToFilter(int $filterId): static
    {
        $this->filterIds[] = $filterId;
        return $this;
    }

    public function build(): Brand
    {
        $brand = Brand::create([
            'image_id'    => $this->imageId     ?? 1,
            'text_id'     => $this->textId      ?? 1,
            'name'        => $this->name        ?? $this->faker->company(),
            'description' => $this->description ?? $this->faker->text(255),
            'type'        => $this->type        ?? $this->faker->word(),
        ]);

        foreach ($this->filterIds as $filterId) {
            FilterBrand::create([
                'filter_id' => $filterId,
                'brand_id'  => $brand->id,
            ]);
        }

        return $brand;
    }
}
