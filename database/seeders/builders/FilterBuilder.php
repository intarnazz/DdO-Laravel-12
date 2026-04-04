<?php

namespace Database\Seeders\Builders;

use App\Models\Characteristic;
use App\Models\Filter;
use App\Models\TypeFilter;
use Faker\Factory;
use Faker\Generator;

/**
 * Builder for Filter (+ Characteristics + TypeFilter links).
 *
 * Usage:
 *   $result = FilterBuilder::make()
 *       ->name('color')
 *       ->type('color')
 *       ->description('Цвет покрытия')
 *       ->linkToType($entrance_doors_type->id)
 *       ->withCharacteristic(imageId: 1, name: 'F5F5F5')
 *       ->withCharacteristic(imageId: 2, name: 'D59057')
 *       ->build();
 *
 *   $filter         = $result['filter'];           // Filter
 *   $characteristics = $result['characteristics']; // Characteristic[]
 *
 *   // Batch characteristics from an array:
 *   FilterBuilder::make()
 *       ->name('size')
 *       ->type('size')
 *       ->linkToType($type->id)
 *       ->withCharacteristics([
 *           ['name' => '960×2050', 'image_id' => 1],
 *           ['name' => '860×2050', 'image_id' => 2],
 *       ])
 *       ->build();
 */
class FilterBuilder
{
    protected Generator $faker;

    /**
     * Shared faker instance across all FilterBuilder instances.
     * Required so that ->unique() state is preserved between make() calls
     * and we never generate a duplicate filter name.
     */
    private static ?Generator $sharedFaker = null;

    protected ?string $name        = null;
    protected ?string $type        = null;
    protected ?string $description = null;

    /** @var int[] */
    protected array $typeIds = [];

    /** @var array<int, array{name: string, image_id: int, description: ?string}> */
    protected array $characteristics = [];

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

    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Filter type slug (e.g. 'color', 'brand', 'size').
     * Defaults to name if not set.
     */
    public function type(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Links the Filter to a Type via TypeFilter.
     */
    public function linkToType(int $typeId): static
    {
        $this->typeIds[] = $typeId;
        return $this;
    }

    /**
     * Adds a single characteristic definition.
     */
    public function withCharacteristic(
        int     $imageId,
        string  $name,
        ?string $description = null
    ): static {
        $this->characteristics[] = [
            'image_id'    => $imageId,
            'name'        => $name,
            'description' => $description,
        ];
        return $this;
    }

    /**
     * Adds multiple characteristic definitions at once.
     *
     * @param array<int, array{name: string, image_id?: int, description?: string}> $items
     */
    public function withCharacteristics(array $items): static
    {
        foreach ($items as $item) {
            $this->characteristics[] = [
                'image_id'    => $item['image_id']    ?? 1,
                'name'        => $item['name'],
                'description' => $item['description'] ?? null,
            ];
        }
        return $this;
    }

    /**
     * @return array{
     *     filter: Filter,
     *     characteristics: Characteristic[],
     * }
     */
    public function build(): array
    {
        $name = $this->name ?? static::$sharedFaker->unique()->word();

        // Фильтры — общий справочник. Если фильтр с таким именем уже существует
        // (например, 'color' или 'brand' из другого каталога) — берём его,
        // не создаём дубль.
        $filter = Filter::firstOrCreate(
            ['name' => $name],
            [
                'description' => $this->description ?? $this->faker->text(255),
                'type'        => $this->type        ?? $name,
            ]
        );

        foreach ($this->typeIds as $typeId) {
            // TypeFilter тоже идемпотентен: один фильтр может быть привязан
            // к нескольким типам, но не дважды к одному.
            TypeFilter::firstOrCreate([
                'type_id'   => $typeId,
                'filter_id' => $filter->id,
            ]);
        }

        // Если фильтр уже существовал — его характеристики тоже уже есть,
        // возвращаем их без повторного создания.
        if (!$filter->wasRecentlyCreated) {
            return [
                'filter'          => $filter,
                'characteristics' => $filter->characteristics()->get()->all(),
            ];
        }

        $createdCharacteristics = [];

        foreach ($this->characteristics as $charData) {
            $createdCharacteristics[] = Characteristic::create([
                'image_id'    => $charData['image_id'],
                'filter_id'   => $filter->id,
                'name'        => $charData['name'],
                'description' => $charData['description'] ?? $this->faker->text(255),
            ]);
        }

        return [
            'filter'          => $filter,
            'characteristics' => $createdCharacteristics,
        ];
    }
}
