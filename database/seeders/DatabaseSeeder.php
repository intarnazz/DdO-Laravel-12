<?php

namespace Database\Seeders;

use Database\Seeders\builders\CatalogBuilder;
use Database\Seeders\builders\ContactBuilder;
use Database\Seeders\builders\DocumentationBuilder;
use Database\Seeders\builders\ImageBuilder;
use Database\Seeders\builders\TextBuilder;
use Database\Seeders\builders\VacancyBuilder;
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

        $this->command->info('');
        $this->command->info('┌─────────────────────────────────────────┐');
        $this->command->info('│           DATABASE SEEDER START         │');
        $this->command->info('└─────────────────────────────────────────┘');
        $this->command->info('');

        $this->command->info('📞 [1/6] Создание контактов...');

        ContactBuilder::make()
            ->name('phone')
            ->description('+79004807844')
            ->link('+79004807844')
            ->type('phone')
            ->build();
        $this->command->line('    ✔ phone: +79004807844');

        ContactBuilder::make()
            ->name('address')
            ->description('Ул. Терешковой, 12, Александров')
            ->build();
        $this->command->line('    ✔ address: Ул. Терешковой, 12, Александров');

        ContactBuilder::make()->name('whatsapp')->build();
        $this->command->line('    ✔ whatsapp');
        ContactBuilder::make()->name('whatsapp')->build();
        $this->command->line('    ✔ whatsapp');
        ContactBuilder::make()->name('odnoklassniki')->build();
        $this->command->line('    ✔ odnoklassniki');
        ContactBuilder::make()->name('vk')->build();
        $this->command->line('    ✔ vk');

        $this->command->info('    → Контакты созданы (6 шт.)');

        // ----------------------------------------------------------------
        // 2. Вакансии, тексты, документации
        // ----------------------------------------------------------------

        $this->command->info('');
        $this->command->info('📋 [2/6] Создание вакансий, текстов, документаций (17 итераций)...');

        foreach (range(1, 17) as $i) {
            VacancyBuilder::make()->build();

            $text = TextBuilder::make()->build();
            $rootDoc = DocumentationBuilder::make()->textId($text->id)->build();
            DocumentationBuilder::make()->textId($text->id)->selfId($rootDoc->id)->build();

            $this->command->line("    ✔ Итерация {$i}/17 — vacancy + contact + text + doc (root) + doc (child)");
        }

        $this->command->info('    → Готово: 17 вакансий, 17 контактов, 17 текстов, 34 документации');

        // ----------------------------------------------------------------
        // 3. Изображения каталогов
        // ----------------------------------------------------------------

        $this->command->info('');
        $this->command->info('🖼  [3/6] Загрузка изображений из Storage...');

        $entranceDoorsImages = ImageBuilder::fromFiles(
            Storage::disk('public')->files('/entrance-doors')
        );
        $this->command->line('    ✔ entrance-doors: ' . count($entranceDoorsImages) . ' шт.');

        $interiorDoorsImages = ImageBuilder::fromFiles(
            Storage::disk('public')->files('/interior-doors')
        );
        $this->command->line('    ✔ interior-doors: ' . count($interiorDoorsImages) . ' шт.');

        $kitchensImages = ImageBuilder::fromFiles(
            Storage::disk('public')->files('/kitchens')
        );
        $this->command->line('    ✔ kitchens: ' . count($kitchensImages) . ' шт.');

        $logoImages = ImageBuilder::fromFiles(
            Storage::disk('public')->files('/logo'),
            'svg'
        );
        $this->command->line('    ✔ logo (svg): ' . count($logoImages) . ' шт.');

        $this->command->info('    → Изображения загружены: ' . (count($entranceDoorsImages) + count($interiorDoorsImages) + count($kitchensImages) + count($logoImages)) . ' шт. всего');

        // ----------------------------------------------------------------
        // 4. Каталог «Входные Двери» — здесь создаются общие бренды
        // ----------------------------------------------------------------

        $this->command->info('');
        $this->command->info('🚪 [4/6] Каталог «Входные Двери»...');
        $this->command->line('    — изображений: ' . count($entranceDoorsImages));
        $this->command->line('    — продуктов на итерацию: 10');
        $this->command->line('    — ожидаемо продуктов: ' . count($entranceDoorsImages) * 10);
        $this->command->line('    — создаются общие бренды из svg-логотипов (' . count($logoImages) . ' шт.)');

        $entranceCatalog = CatalogBuilder::make()
            ->name('entrance-doors')
            ->visibleName('Входные Двери')
            ->withImages($entranceDoorsImages)
            ->withColorFilter([
                'F5F5F5', 'FFFCD6', 'D59057', '94471E', '75300B',
                '929492', '35170C', '000000', '000001', '000002',
            ])
            ->withColorsPerProduct(2)
            ->withBrandFilter()                    // ← бренды создаются здесь
            ->withBrandImages($logoImages)         // ← svg-логотипы для брендов
            ->withSizeFilter(array_fill(0, 10, '960×2050'))
            ->withGeneratedFilters(characteristicsPerFilter: 10)
            ->withTags()
            ->withVariants()
            ->withProducts(perIteration: 10)
            ->withDefaultOptions()
            ->withSecondaryImage(true)
            ->build();

        $commonBrands = $entranceCatalog['brands'];

        $this->command->info('    → Готово: ' . count($entranceCatalog['products']) . ' продуктов, ' . count($commonBrands) . ' брендов создано');

        // ----------------------------------------------------------------
        // 5. Каталог «Межкомнатные Двери» — используем те же бренды
        // ----------------------------------------------------------------

        $this->command->info('');
        $this->command->info('🚪 [5/6] Каталог «Межкомнатные Двери»...');
        $this->command->line('    — изображений: ' . count($interiorDoorsImages));
        $this->command->line('    — ожидаемо продуктов: ' . count($interiorDoorsImages) * 10);
        $this->command->line('    — используются общие бренды (' . count($commonBrands) . ' шт.)');

        $interiorCatalog = CatalogBuilder::make()
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

        $this->command->info('    → Готово: ' . count($interiorCatalog['products']) . ' продуктов');

        // ----------------------------------------------------------------
        // 6. Каталог «кухни» — используем те же бренды
        // ----------------------------------------------------------------

        $this->command->info('');
        $this->command->info('🍳 [6/6] Каталог «Кухни»...');
        $this->command->line('    — изображений: ' . count($kitchensImages));
        $this->command->line('    — ожидаемо продуктов: ' . count($kitchensImages) * 10);
        $this->command->line('    — используются общие бренды (' . count($commonBrands) . ' шт.)');

        $kitchensCatalog = CatalogBuilder::make()
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

        $this->command->info('    → Готово: ' . count($kitchensCatalog['products']) . ' продуктов');

        // ----------------------------------------------------------------
        // Итог
        // ----------------------------------------------------------------

        $totalProducts = count($entranceCatalog['products'])
            + count($interiorCatalog['products'])
            + count($kitchensCatalog['products']);

        $this->command->info('');
        $this->command->info('┌─────────────────────────────────────────┐');
        $this->command->info('│            SEEDING COMPLETED ✅          │');
        $this->command->info('├─────────────────────────────────────────┤');
        $this->command->info('│  Продуктов всего : ' . str_pad($totalProducts, 21) . '│');
        $this->command->info('│  Брендов         : ' . str_pad(count($commonBrands), 21) . '│');
        $this->command->info('│  Каталогов       : ' . str_pad(3, 21) . '│');
        $this->command->info('└─────────────────────────────────────────┘');
        $this->command->info('');
    }
}
