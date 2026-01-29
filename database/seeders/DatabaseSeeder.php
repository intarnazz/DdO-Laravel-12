<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Characteristic;
use App\Models\Contact;
use App\Models\Documentation;
use App\Models\Filter;
use App\Models\FilterBrand;
use App\Models\FilterCharacteristic;
use App\Models\Gallery;
use App\Models\Image;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductFilter;
use App\Models\Tag;
use App\Models\Text;
use App\Models\Type;
use App\Models\TypeFilter;
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

        Contact::create([
            'name' => 'phone',
            'description' => '+79004807844',
            'link' => '+79004807844',
            'type' => 'phone',
        ]);

        Contact::create([
            'name' => 'address',
            'description' => 'Ул. Терешковой, 12, Александров',
            'link' => $faker->url,
        ]);

        Contact::create([
            'name' => 'whatsapp',
            'description' => $faker->text(255),
            'link' => $faker->url,
        ]);

        Contact::create([
            'name' => 'whatsapp',
            'description' => $faker->text(255),
            'link' => $faker->url,
        ]);

        Contact::create([
            'name' => 'odnoklassniki',
            'description' => $faker->text(255),
            'link' => $faker->url,
        ]);

        Contact::create([
            'name' => 'vk',
            'description' => $faker->text(255),
            'link' => $faker->url,
        ]);

        $filter_brand = Filter::create([
            'name' => 'brand',
            'description' => $faker->text(255),
            'type' => 'brand',
        ]);

        foreach (range(1, 17) as $i) {
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
            FilterBrand::create([
                'filter_id' => $filter_brand->id,
                'brand_id' => $i,
            ]);
            Filter::create([
                'name' => $faker->word(),
                'description' => $faker->text(255),
                'type' => $faker->word(),
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
            foreach (range(1, 10) as $p) {
                Product::create([
                    'image_id' => $i,
                    'type_id' => $i,
                    'tag_id' => $i,
                    'variant_id' => $i,
                    'brand_id' => $i,
                    'name' => $faker->unique()->text(16) . ' / ' . $faker->unique()->text(16),
                    'description' => $faker->text(255),
                    'video_url' => $faker->url(),
                    'price' => $faker->biasedNumberBetween(10000, 50000),
                    'article' => $faker->unique()->word() . '-' . $faker->word(),
                ]);
                ProductFilter::create([
                    'product_id' => $i * $p,
                    'filter_id' => $i,
                ]);
                Characteristic::create([
                    'image_id' => $i,
                    'filter_id' => $i,
                    'product_id' => $i * $p,
                    'name' => $faker->word(),
                    'description' => $faker->text(255),
                ]);
                Gallery::create([
                    'image_id' => $i,
                    'product_id' => $i * $p,
                ]);
                Option::create([
                    'image_id' => $i,
                    'product_id' => $i * $p,
                    'price' => $faker->biasedNumberBetween(1000, 20000),
                    'name' => $faker->word(),
                    'description' => $faker->text(255),
                ]);
            }
        }
    }
}
