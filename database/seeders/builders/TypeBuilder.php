<?php

namespace Database\Seeders\Builders;

use App\Models\Filter;
use App\Models\FilterBrand;
use App\Models\Tag;
use App\Models\Type;
use App\Models\TypeFilter;
use App\Models\Variant;
use Faker\Factory;
use Faker\Generator;

/**
 * Fluent builder for Type and all its related entities.
 *
 * Usage:
 *
 *   $type = TypeBuilder::make()
 *       ->name('entrance-doors')
 *       ->visibleName('Входные Двери')
 *       ->description('...')
 *       ->selfId(1)                         // optional parent
 *       ->withFilter('color', 'color')       // name, type
 *       ->withFilter('brand', 'brand')
 *       ->withBrandFilter($brandIds)         // links brands to 'brand' filter
 *       ->withTag('Акция', 'Товар со скидкой')
 *       ->withTag('Новинка')
 *       ->withVariant('Левое открывание', '...')
 *       ->withVariant('Правое открывание')
 *       ->build();
 */
class TypeBuilder
{
    protected Generator $faker;

    // Type fields
    protected string $name        = '';
    protected ?string $visibleName = null;
    protected ?string $description = null;
    protected ?int    $selfId      = null;

    // Related entities to create
    /** @var array<int, array{name: string, description: string, type: string}> */
    protected array $filters = [];

    /** @var array<int, array{name: string, description: string}> */
    protected array $tags = [];

    /** @var array<int, array{name: string, description: string}> */
    protected array $variants = [];

    /**
     * Brand IDs to associate with the first filter that has type === 'brand'.
     * @var int[]
     */
    protected array $brandIds = [];

    // -----------------------------------------------------------------------
    // Factory
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
    // Type fields
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

    /**
     * Sets the parent type (self_id).
     */
    public function selfId(int $selfId): static
    {
        $this->selfId = $selfId;
        return $this;
    }

    // -----------------------------------------------------------------------
    // Filters
    // -----------------------------------------------------------------------

    /**
     * Adds a filter to be created and linked to this Type.
     *
     * @param string      $name        Filter name (e.g. 'color', 'brand', 'size')
     * @param string|null $type        Filter type; defaults to $name
     * @param string|null $description Defaults to a faker sentence
     */
    public function withFilter(
        string  $name,
        ?string $type = null,
        ?string $description = null
    ): static {
        $this->filters[] = [
            'name'        => $name,
            'type'        => $type ?? $name,
            'description' => $description ?? $this->faker->text(255),
        ];
        return $this;
    }

    /**
     * Associates the given brand IDs with the filter whose type === 'brand'.
     * Call this after ->withFilter('brand', 'brand').
     *
     * @param int[] $brandIds
     */
    public function withBrandFilter(array $brandIds): static
    {
        $this->brandIds = $brandIds;
        return $this;
    }

    // -----------------------------------------------------------------------
    // Tags
    // -----------------------------------------------------------------------

    /**
     * Adds a tag linked to this Type.
     *
     * @param string      $name
     * @param string|null $description Defaults to a faker sentence
     */
    public function withTag(string $name, ?string $description = null): static
    {
        $this->tags[] = [
            'name'        => $name,
            'description' => $description ?? $this->faker->text(255),
        ];
        return $this;
    }

    // -----------------------------------------------------------------------
    // Variants
    // -----------------------------------------------------------------------

    /**
     * Adds a variant linked to this Type.
     *
     * @param string      $name
     * @param string|null $description Defaults to a faker sentence
     */
    public function withVariant(string $name, ?string $description = null): static
    {
        $this->variants[] = [
            'name'        => $name,
            'description' => $description ?? $this->faker->text(255),
        ];
        return $this;
    }

    // -----------------------------------------------------------------------
    // Build
    // -----------------------------------------------------------------------

    /**
     * Persists the Type and all configured related entities.
     *
     * @return array{
     *     type: Type,
     *     filters: Filter[],
     *     tags: Tag[],
     *     variants: Variant[],
     * }
     */
    public function build(): array
    {
        // 1. Create the Type
        $typeData = [
            'name'        => $this->name,
            'description' => $this->description ?? $this->faker->text(255),
        ];

        if ($this->visibleName !== null) {
            $typeData['visible_name'] = $this->visibleName;
        }

        if ($this->selfId !== null) {
            $typeData['self_id'] = $this->selfId;
        }

        $type = Type::create($typeData);

        // 2. Create Filters and link them via TypeFilter
        $createdFilters = [];

        foreach ($this->filters as $filterData) {
            $filter = Filter::create([
                'name'        => $filterData['name'],
                'description' => $filterData['description'],
                'type'        => $filterData['type'],
            ]);

            TypeFilter::create([
                'type_id'   => $type->id,
                'filter_id' => $filter->id,
            ]);

            // If this is a brand filter and we have brand IDs — link them
            if ($filterData['type'] === 'brand' && !empty($this->brandIds)) {
                foreach ($this->brandIds as $brandId) {
                    FilterBrand::create([
                        'filter_id' => $filter->id,
                        'brand_id'  => $brandId,
                    ]);
                }
            }

            $createdFilters[] = $filter;
        }

        // 3. Create Tags
        $createdTags = [];

        foreach ($this->tags as $tagData) {
            $createdTags[] = Tag::create([
                'type_id'     => $type->id,
                'name'        => $tagData['name'],
                'description' => $tagData['description'],
            ]);
        }

        // 4. Create Variants
        $createdVariants = [];

        foreach ($this->variants as $variantData) {
            $createdVariants[] = Variant::create([
                'type_id'     => $type->id,
                'name'        => $variantData['name'],
                'description' => $variantData['description'],
            ]);
        }

        return [
            'type'     => $type,
            'filters'  => $createdFilters,
            'tags'     => $createdTags,
            'variants' => $createdVariants,
        ];
    }

    /**
     * Adds a Tag to an already-existing Type.
     * Use when you need to attach tags to a Type that was created earlier.
     */
    public static function addTagTo(int $typeId, ?string $name = null, ?string $description = null): \App\Models\Tag
    {
        $faker = Factory::create();

        return \App\Models\Tag::create([
            'type_id'     => $typeId,
            'name'        => $name        ?? $faker->word(),
            'description' => $description ?? $faker->text(255),
        ]);
    }

    /**
     * Adds a Variant to an already-existing Type.
     * Use when you need to attach variants to a Type that was created earlier.
     */
    public static function addVariantTo(int $typeId, ?string $name = null, ?string $description = null): \App\Models\Variant
    {
        $faker = Factory::create();

        return \App\Models\Variant::create([
            'type_id'     => $typeId,
            'name'        => $name        ?? $faker->word(),
            'description' => $description ?? $faker->text(255),
        ]);
    }
}
