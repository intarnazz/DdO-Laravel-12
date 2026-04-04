<?php

namespace Database\Seeders\Builders;

use App\Models\Image;

/**
 * Builder for Image.
 *
 * Usage:
 *   $image = ImageBuilder::make()
 *       ->name('door-front')
 *       ->path('door-front.jpg')
 *       ->build();
 *
 *   // From a Storage::disk('public')->files('/') list:
 *   $images = ImageBuilder::fromFiles($files);
 */
class ImageBuilder
{
    protected string $name = '';
    protected string $path = '';

    public static function make(): static
    {
        return new static();
    }

    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function path(string $path): static
    {
        $this->path = $path;
        return $this;
    }

    public function build(): Image
    {
        return Image::create([
            'name' => $this->name,
            'path' => $this->path,
        ]);
    }

    /**
     * Creates Image records from a list of file paths (Storage::disk()->files()).
     *
     * @param  string[] $files
     * @return Image[]
     */
    public static function fromFiles(array $files): array
    {
        $images = [];
        foreach ($files as $file) {
            $images[] = static::make()
                ->name(pathinfo($file, PATHINFO_FILENAME))
                ->path($file)
                ->build();
        }
        return $images;
    }
}
