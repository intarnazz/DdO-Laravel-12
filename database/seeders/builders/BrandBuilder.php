<?php

namespace Database\Seeders\builders;

use App\Models\Brand;
use Faker\Factory;
use Faker\Generator;

/**
 * Builder for Brand.
 *
 * Usage:
 *   $brand = BrandBuilder::make()
 *       ->imageId(1)
 *       ->textId(1)
 *       ->filterId(42)
 *       ->name('Torex')
 *       ->description('Российский производитель...')
 *       ->build();
 */
class BrandBuilder
{
    protected Generator $faker;

    private static ?Generator $sharedFaker = null;

    protected ?int    $imageId     = null;
    protected ?int    $textId      = null;
    protected ?int    $filterId    = null;
    protected ?string $name        = null;
    protected ?string $description = null;

    public static function make(): static
    {
        return new static();
    }

    public function __construct()
    {
        $this->faker = Factory::create();

        if (static::$sharedFaker === null) {
            static::$sharedFaker = Factory::create();
        }
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


    /**
     * Sets the filter_id on the Brand (direct FK, replaces FilterBrand pivot).
     */
    public function filterId(int $filterId): static
    {
        $this->filterId = $filterId;
        return $this;
    }

    public function build(): Brand
    {
        return Brand::create([
            'image_id'    => $this->imageId  ?? 1,
            'text_id'     => $this->textId   ?? 1,
            'filter_id'   => $this->filterId ?? 1,
            'name'        => $this->name        ?? static::$sharedFaker->unique()->company(),
            'description' => $this->description ?? $this->faker->text(255),
        ]);
    }
}
