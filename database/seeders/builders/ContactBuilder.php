<?php

namespace Database\Seeders\builders;

use App\Models\Contact;
use Faker\Factory;
use Faker\Generator;

/**
 * Builder for Contact.
 *
 * Usage:
 *   ContactBuilder::make()
 *       ->name('phone')
 *       ->description('+79004807844')
 *       ->link('+79004807844')
 *       ->build();
 *
 *   ContactBuilder::make()
 *       ->name('address')
 *       ->description('Ул. Терешковой, 12, Александров')
 *       ->link('https://maps...')
 *       ->build();
 *
 *   ContactBuilder::make()->name('vk')->link('https://vk.com/...')->build();
 *   ContactBuilder::make()->name('whatsapp')->link('https://wa.me/...')->build();
 *   ContactBuilder::make()->name('odnoklassniki')->link('https://ok.ru/...')->build();
 */
class ContactBuilder
{
    protected Generator $faker;

    protected string  $name        = '';
    protected ?string $description = null;
    protected ?string $link        = null;

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

    public function link(string $link): static
    {
        $this->link = $link;
        return $this;
    }

    public function build(): Contact
    {
        $data = [
            'name'        => $this->name,
            'description' => $this->description ?? $this->faker->text(255),
            'link'        => $this->link        ?? $this->faker->url(),
        ];

        return Contact::create($data);
    }
}
