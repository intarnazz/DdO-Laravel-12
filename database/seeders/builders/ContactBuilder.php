<?php

namespace Database\Seeders\Builders;

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
 *       ->type('phone')
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
    protected ?string $type        = null;

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

    /**
     * Optional explicit type (e.g. 'phone').
     * If not set — omitted from the payload (stays null in DB).
     */
    public function type(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function build(): Contact
    {
        $data = [
            'name'        => $this->name,
            'description' => $this->description ?? $this->faker->text(255),
            'link'        => $this->link        ?? $this->faker->url(),
        ];

        if ($this->type !== null) {
            $data['type'] = $this->type;
        }

        return Contact::create($data);
    }
}
