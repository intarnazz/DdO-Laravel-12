<?php

namespace Database\Seeders\Builders;

use App\Models\Option;
use Faker\Factory;
use Faker\Generator;

/**
 * Builder for Option (product add-on / комплектация).
 *
 * In the seeder each product gets:
 *   - 'коробка'   (is_set = true,  default_count = 1)
 *   - 'наличник'  (is_set = false, default_count = 5)
 *   - 2 random options
 *
 * Usage:
 *   OptionBuilder::make()
 *       ->productId($product->id)
 *       ->imageId($product->image_id)
 *       ->name('коробка')
 *       ->price(5000)
 *       ->isSet(true)
 *       ->defaultCount(1)
 *       ->build();
 *
 *   OptionBuilder::make()
 *       ->productId($product->id)
 *       ->name('наличник')
 *       ->defaultCount(5)
 *       ->build();
 *
 *   // Attach the standard set (коробка + наличник + 2 random) to a product:
 *   OptionBuilder::attachDefaults($product);
 */
class OptionBuilder
{
    protected Generator $faker;

    protected ?int    $productId    = null;
    protected ?int    $imageId      = null;
    protected ?string $name         = null;
    protected ?string $description  = null;
    protected ?int    $price        = null;
    protected bool    $isSet        = false;
    protected int     $defaultCount = 0;

    public static function make(): static
    {
        return new static();
    }

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function productId(int $productId): static
    {
        $this->productId = $productId;
        return $this;
    }

    public function imageId(int $imageId): static
    {
        $this->imageId = $imageId;
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

    public function price(int $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function isSet(bool $isSet): static
    {
        $this->isSet = $isSet;
        return $this;
    }

    public function defaultCount(int $count): static
    {
        $this->defaultCount = $count;
        return $this;
    }

    public function build(): Option
    {
        return Option::create([
            'image_id'      => $this->imageId      ?? 1,
            'product_id'    => $this->productId    ?? 1,
            'price'         => $this->price        ?? $this->faker->biasedNumberBetween(1000, 20000),
            'name'          => $this->name         ?? $this->faker->word(),
            'description'   => $this->description  ?? $this->faker->text(255),
            'is_set'        => $this->isSet,
            'default_count' => $this->defaultCount,
        ]);
    }

    /**
     * Creates the standard option set for a product:
     * коробка (is_set, count=1) + наличник (count=5) + 2 random.
     *
     * @param  \App\Models\Product $product
     * @return Option[]
     */
    public static function attachDefaults(\App\Models\Product $product): array
    {
        $faker   = Factory::create();
        $options = [];

        $options[] = static::make()
            ->productId($product->id)
            ->imageId($product->image_id ?? 1)
            ->name('коробка')
            ->price($faker->biasedNumberBetween(1000, 20000))
            ->isSet(true)
            ->defaultCount(1)
            ->build();

        $options[] = static::make()
            ->productId($product->id)
            ->imageId($product->image_id ?? 1)
            ->name('наличник')
            ->price($faker->biasedNumberBetween(1000, 20000))
            ->defaultCount(5)
            ->build();

        foreach (range(1, 2) as $_) {
            $options[] = static::make()
                ->productId($product->id)
                ->imageId($product->image_id ?? 1)
                ->price($faker->biasedNumberBetween(1000, 20000))
                ->build();
        }

        return $options;
    }
}
