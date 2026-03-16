<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Characteristic;
use App\Models\Contact;
use App\Models\Documentation;
use App\Models\Filter;
use App\Models\FilterBrand;
use App\Models\Gallery;
use App\Models\Image;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductCharacteristic;
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
                'second_path' => $file,
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

        $entrance_doors_type = Type::create([
            'name' => 'entrance-doors',
            'visible_name' => 'Входные Двери',
            'description' => $faker->text(255),
        ]);

        $filter_color = Filter::create([
            'name' => 'color',
            'description' => $faker->text(255),
            'type' => 'color'
        ]);

        $colors = [
            'F5F5F5',
            'FFFCD6',
            'D59057',
            '94471E',
            '75300B',
            '929492',
            '35170C',
            '000000',
            '000000',
            '000000',
        ];

        $products = [];

        foreach (range(1, 10) as $i) {
            Characteristic::create([
                'image_id' => $i,
                'filter_id' => $filter_color->id,
                'name' => $colors[$i - 1],
                'description' => $faker->text(255),
            ]);
        }

        $filter_brand = Filter::create([
            'name' => 'brand',
            'description' => $faker->text(255),
            'type' => 'brand'
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

            $name = $faker->unique()->word();

            Filter::create([
                'name' => $name,
                'description' => $faker->text(255),
                'type' => $name,
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
                'type_id' => $entrance_doors_type->id,
                'filter_id' => $i,
            ]);
            Tag::create([
                'type_id' => $entrance_doors_type->id,
                'name' => $faker->word(),
                'description' => $faker->text(255),
            ]);
            Variant::create([
                'type_id' => $entrance_doors_type->id,
                'name' => $faker->word(),
                'description' => $faker->text(255),
            ]);
            foreach (range(1, 10) as $p) {
                $product = Product::create([
                    'image_id' => $i,
                    'type_id' => $entrance_doors_type->id,
                    'tag_id' => $i,
                    'variant_id' => $i,
                    'brand_id' => $i,
                    'name' => $faker->text(16) . ' / ' . $faker->text(16),
                    'description' => $faker->text(255),
                    'video_url' => $faker->url(),
                    'price' => $faker->biasedNumberBetween(10000, 50000),
                    'article' => $faker->word() . '-' . $faker->word(),
                    'products.discount' => $faker->boolean(33) ? $faker->biasedNumberBetween(0, 100) : 0,
                    'is_stock' => true,
                    'is_in_store' => $faker->boolean(),
                ]);
                $products[] = $product;
                if ($i > 1) {
                    Characteristic::create([
                        'image_id' => $i,
                        'filter_id' => $i,
                        'name' => $faker->word(),
                        'description' => $faker->text(255),
                    ]);
                }
                Gallery::create([
                    'image_id' => $i,
                    'product_id' => $product->id,
                ]);
                Option::create([
                    'image_id' => $i,
                    'product_id' => $product->id,
                    'price' => $faker->biasedNumberBetween(1000, 20000),
                    'name' => $faker->word(),
                    'description' => $faker->text(255),
                ]);
            }
        }
        $productsCount = 170;
        $filtersCount = 17;
        $characteristicsPerFilter = 10;

        for ($productId = 1; $productId <= $productsCount; $productId++) {

            for ($filterIndex = 0; $filterIndex < $filtersCount; $filterIndex++) {

                // начало диапазона characteristic_id для фильтра
                $filterStartCharacteristicId =
                    $filterIndex * $characteristicsPerFilter + 1;

                // индекс характеристики (0–9), каждая на 17 продуктов
                $characteristicOffset = intdiv($productId - 1, 17);

                $characteristicId =
                    $filterStartCharacteristicId + $characteristicOffset;

                ProductCharacteristic::create([
                    'product_id' => $productId,
                    'characteristic_id' => $characteristicId,
                ]);
            }
        }

        $filter_size = Filter::create([
            'name' => 'size',
            'description' => $faker->text(255),
            'type' => 'size'
        ]);

        TypeFilter::create([
            'type_id' => $entrance_doors_type->id,
            'filter_id' => $filter_size->id,
        ]);

        $sizes = [
            '960×2050',
            '960×2050',
            '960×2050',
            '960×2050',
            '960×2050',
            '960×2050',
            '960×2050',
            '960×2050',
            '960×2050',
            '960×2050',
        ];

        $characteristic_sizes = [];

        foreach (range(1, 10) as $i) {
            $characteristic_size = Characteristic::create([
                'image_id' => $i,
                'filter_id' => $filter_size->id,
                'name' => $sizes[$i - 1],
                'description' => $faker->text(255),
            ]);
            $characteristic_sizes[] = $characteristic_size;
        }

        foreach ($products as $product) {
            foreach ($characteristic_sizes as $characteristic_size) {
                ProductCharacteristic::create([
                    'product_id' => $product->id,
                    'characteristic_id' => $characteristic_size->id,
                ]);
            }
        }

    }
}
