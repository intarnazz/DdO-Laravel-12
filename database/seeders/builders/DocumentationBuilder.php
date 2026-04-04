<?php

namespace Database\Seeders\Builders;

use App\Models\Documentation;
use Faker\Factory;
use Faker\Generator;

/**
 * Builder for Documentation.
 *
 * In the seeder two Documentation records per iteration were created:
 *   - a root doc  (no self_id)
 *   - a child doc (self_id = parent id)
 *
 * Usage:
 *   // Root documentation:
 *   $doc = DocumentationBuilder::make()
 *       ->textId(1)
 *       ->name('Сертификаты')
 *       ->description('...')
 *       ->build();
 *
 *   // Child documentation:
 *   $child = DocumentationBuilder::make()
 *       ->textId(1)
 *       ->name('ГОСТ 31173-2016')
 *       ->selfId($doc->id)
 *       ->build();
 */
class DocumentationBuilder
{
    protected Generator $faker;

    protected ?int    $textId      = null;
    protected ?string $name        = null;
    protected ?string $description = null;
    protected ?int    $selfId      = null;

    public static function make(): static
    {
        return new static();
    }

    public function __construct()
    {
        $this->faker = Factory::create();
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
     * Makes this a child of another Documentation record.
     */
    public function selfId(int $selfId): static
    {
        $this->selfId = $selfId;
        return $this;
    }

    public function build(): Documentation
    {
        $data = [
            'text_id'     => $this->textId      ?? 1,
            'name'        => $this->name        ?? $this->faker->words(3, true),
            'description' => $this->description ?? $this->faker->text(255),
        ];

        if ($this->selfId !== null) {
            $data['self_id'] = $this->selfId;
        }

        return Documentation::create($data);
    }
}
