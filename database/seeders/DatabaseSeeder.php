<?php

namespace Database\Seeders;

use Database\Seeders\Builders\CatalogBuilder;
use Database\Seeders\Builders\ContactBuilder;
use Database\Seeders\Builders\DocumentationBuilder;
use Database\Seeders\Builders\ImageBuilder;
use Database\Seeders\Builders\TextBuilder;
use Database\Seeders\Builders\VacancyBuilder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ----------------------------------------------------------------
        // 1. Контакты
        // ----------------------------------------------------------------

        ContactBuilder::make()
            ->name('phone')
            ->description('+79004807844')
            ->link('+79004807844')
            ->type('phone')
            ->build();

        ContactBuilder::make()
            ->name('address')
            ->description('Ул. Терешковой, 12, Александров')
            ->build();

        ContactBuilder::make()->name('whatsapp')->build();
        ContactBuilder::make()->name('whatsapp')->build();
        ContactBuilder::make()->name('odnoklassniki')->build();
        ContactBuilder::make()->name('vk')->build();

        // ----------------------------------------------------------------
        // 2. Вакансии, тексты, документации
        // ----------------------------------------------------------------

        foreach (range(1, 17) as $_) {
            VacancyBuilder::make()->build();
            ContactBuilder::make()->build();

            $text = TextBuilder::make()->build();
            $rootDoc = DocumentationBuilder::make()->textId($text->id)->build();
            DocumentationBuilder::make()->textId($text->id)->selfId($rootDoc->id)->build();
        }

        // ----------------------------------------------------------------
        // 3. Изображения каталогов
        // ----------------------------------------------------------------

        $entranceDoorsImages = ImageBuilder::fromFiles(
            Storage::disk('public')->files('/entrance-doors')
        );

        $interiorDoorsImages = ImageBuilder::fromFiles(
            Storage::disk('public')->files('/interior-doors')
        );

        $kitchensImages = ImageBuilder::fromFiles(
            Storage::disk('public')->files('/kitchens')
        );

        // ----------------------------------------------------------------
        // 4. Каталог «Входные Двери» — здесь создаются общие бренды
        // ----------------------------------------------------------------

        $entranceCatalog = CatalogBuilder::make()
            ->name('entrance-doors')
            ->visibleName('Входные Двери')
            ->withImages($entranceDoorsImages)
            ->withColorFilter([
                'F5F5F5', 'FFFCD6', 'D59057', '94471E', '75300B',
                '929492', '35170C', '000000', '000000', '000000',
            ])
            ->withColorsPerProduct(2)
            ->withBrandFilter()                    // ← бренды создаются здесь
            ->withSizeFilter(array_fill(0, 10, '960×2050'))
            ->withGeneratedFilters(characteristicsPerFilter: 10)
            ->withTags()
            ->withVariants()
            ->withProducts(perIteration: 10)
            ->withDefaultOptions()
            ->withSecondaryImage(true)
            ->build();

        // Бренды теперь общие
        $commonBrands = $entranceCatalog['brands'];

        // ----------------------------------------------------------------
        // 5. Каталог «Межкомнатные Двери» — используем те же бренды
        // ----------------------------------------------------------------

        CatalogBuilder::make()
            ->name('interior-doors')
            ->visibleName('Межкомнатные Двери')
            ->withImages($interiorDoorsImages)
            ->withColorFilter([
                'F5F5F5', 'FFFCD6', 'D59057', '94471E', '75300B',
                '929492', '35170C', '000000', '000000', '000000',
            ])
            ->withColorsPerProduct(1)
            ->withBrandFilter()                    // создаём фильтр brand (нужен для каталога)
            ->withBrands($commonBrands)            // ← используем общие бренды
            ->withSizeFilter(array_fill(0, 10, '960×2050'))
            ->withGeneratedFilters(characteristicsPerFilter: 10)
            ->withTags()
            ->withVariants()
            ->withProducts(perIteration: 10)
            ->withDefaultOptions()
            ->build();

        // ----------------------------------------------------------------
        // 6. Каталог «кухни» — используем те же бренды
        // ----------------------------------------------------------------

        CatalogBuilder::make()
            ->name('kitchens')
            ->visibleName('кухни')
            ->withImages($kitchensImages)
            ->withColorFilter([
                'F5F5F5', 'FFFCD6', 'D59057', '94471E', '75300B',
                '929492', '35170C', '000000', '000000', '000000',
            ])
            ->withColorsPerProduct(2)
            ->withBrandFilter()                    // создаём фильтр brand (нужен для каталога)
            ->withBrands($commonBrands)            // ← используем общие бренды
            ->withGeneratedFilters(characteristicsPerFilter: 10)
            ->withTags()
            ->withVariants()
            ->withProducts(perIteration: 10)
            ->withDefaultOptions()
            ->build();
    }
}
