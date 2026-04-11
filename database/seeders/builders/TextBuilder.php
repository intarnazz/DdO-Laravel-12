<?php

namespace Database\Seeders\builders;

use App\Models\Text;
use Faker\Factory;
use Faker\Generator;

/**
 * Builder for Text.
 *
 * Usage:
 *   $text = TextBuilder::make()->text('Полный текст...')->build();
 *   $text = TextBuilder::make()->build(); // faker text(2000)
 */
class TextBuilder
{
    protected Generator $faker;
    protected ?string   $text = null;

    public static function make(): static
    {
        return new static();
    }

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function text(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function build(): Text
    {
        return Text::create([
            'text' => $this->text ?? $this->faker->text(2000),
        ]);
    }
}
