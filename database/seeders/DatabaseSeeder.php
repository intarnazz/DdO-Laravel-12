<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Factory::create();

        $files = Storage::disk('public')->allFiles();

        foreach ($files as $file) {
            Image::create([
                'name' => pathinfo($file, PATHINFO_FILENAME),
                'path' => $file,
            ]);
        }
    }
}
