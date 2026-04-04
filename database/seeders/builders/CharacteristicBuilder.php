<?php

namespace Database\Seeders\Builders;

use App\Models\Characteristic;
use Faker\Factory;
use Faker\Generator;

/**
 * Builder for Characteristic.
 *
 * A Characteristic belongs to a Filter and optionally has an Image.
 * In the seeder characteristics represent concrete filter values
 * (e.g. a specific color hex or a door size string).
 *
 * Usage:
 *   $char = CharacteristicBuilder::make()
 *       ->imageId(3)
 *       ->filterId($filter->id)
 *       ->name('F5F5F5')
 *       ->description('Белый')
 *       ->build();
 *
 *   // Batch — create one per item in a list:
 *   $chars = CharacteristicBuilder::batch(
 *       filterId: $filter->id,
 *       items: [
 *           ['name' => '960×2050', 'image_id' => 1],
 *           ['name' => '860×2050', 'image_id' => 2],
 *       ]
 *   );
 */
class CharacteristicBuilder
{
    protected Generator $faker;

    protected ?int    $imageId     = null;
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
    }

    public function imageId(int $imageId): static
    {
        $this->imageId = $imageId;
        return $this;
    }

    public function filterId(int $filterId): static
    {
        $this->filterId = $filterId;
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

    public function build(): Characteristic
    {
        return Characteristic::create([
            'image_id'    => $this->imageId     ?? 1,
            'filter_id'   => $this->filterId    ?? 1,
            'name'        => $this->name        ?? $this->faker->word(),
            'description' => $this->description ?? $this->faker->text(255),
        ]);
    }

    /**
     * Creates multiple Characteristics for the same filter.
     *
     * @param  int                                             $filterId
     * @param  array<int, array{name: string, image_id?: int, description?: string}> $items
     * @return Characteristic[]
     */
    public static function batch(int $filterId, array $items): array
    {
        $faker   = Factory::create();
        $results = [];

        foreach ($items as $item) {
            $results[] = static::make()
                ->filterId($filterId)
                ->imageId($item['image_id'] ?? 1)
                ->name($item['name'])
                ->description($item['description'] ?? $faker->text(255))
                ->build();
        }

        return $results;
    }
}
