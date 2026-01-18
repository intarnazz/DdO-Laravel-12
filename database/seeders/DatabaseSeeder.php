<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Contact;
use App\Models\Documentation;
use App\Models\Filter;
use App\Models\Image;
use App\Models\Tag;
use App\Models\Text;
use App\Models\Type;
use App\Models\TypeFilter;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\Variant;
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

        foreach (range(1, 5) as $i) {
            Vacancy::create([
                'name' => $faker->word(),
                'description' => $faker->text(255),
                'price' => $faker->biasedNumberBetween(10000, 50000),
            ]);
            Contact::create([
                'name' => $faker->word(),
                'description' => $faker->text(255),
                'link' => $faker->text(20),
            ]);
            Text::create([
                'text' => $faker->text(2000),
            ]);
            Brand::create([
                'image_id' => $i,
                'text_id' => $i,
                'name' => $faker->word(),
                'description' => $faker->text(255),
                'type' => $faker->word(),
            ]);
            Filter::create([
                'name' => $faker->word(),
                'description' => $faker->text(255),
                'group' => $faker->word(),
                'value' => $faker->biasedNumberBetween(1, 10),
                'value_type' => 'id',
            ]);
            Filter::create([
                'name' => $faker->word(),
                'description' => $faker->text(255),
                'group' => $faker->word(),
                'value' => $faker->word(),
                'value_type' => 'original',
            ]);
            Documentation::create([
                'text_id' => $i,
                'name' => $faker->word(),
                'description' => $faker->text(255),
            ]);
            Documentation::create([
                'text_id' => $i,
                'name' => $faker->word(),
                'description' => $faker->text(255),
                'self_id' => $i,
            ]);
            Type::create([
                'name' => $faker->word(),
                'description' => $faker->text(255),
            ]);
            Type::create([
                'name' => $faker->word(),
                'description' => $faker->text(255),
                'self_id' => $i,
            ]);
            TypeFilter::create([
                'type_id' => $i,
                'filter_id' => $i,
            ]);
            Tag::create([
                'type_id' => $i,
                'name' => $faker->word(),
                'description' => $faker->text(255),
            ]);
            Variant::create([
                'type_id' => $i,
                'name' => $faker->word(),
                'description' => $faker->text(255),
            ]);
        }
    }
}
