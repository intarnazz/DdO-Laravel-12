<?php

namespace Database\Seeders\Builders;

use App\Models\Vacancy;
use Faker\Factory;
use Faker\Generator;

/**
 * Builder for Vacancy.
 *
 * Usage:
 *   VacancyBuilder::make()
 *       ->name('Менеджер по продажам')
 *       ->description('Продажа дверей...')
 *       ->price(35000)
 *       ->build();
 */
class VacancyBuilder
{
    protected Generator $faker;

    protected ?string $name        = null;
    protected ?string $description = null;
    protected ?int    $price       = null;

    public static function make(): static
    {
        return new static();
    }

    public function __construct()
    {
        $this->faker = Factory::create();
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

    public function build(): Vacancy
    {
        return Vacancy::create([
            'name'        => $this->name        ?? $this->faker->jobTitle(),
            'description' => $this->description ?? $this->faker->text(255),
            'price'       => $this->price       ?? $this->faker->biasedNumberBetween(10000, 50000),
        ]);
    }
}
