<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Tags\RelationManagers\ProductsRelationManager as TagProductsRelationManager;
use App\Filament\Resources\Types\RelationManagers\ProductsRelationManager as TypeProductsRelationManager;
use App\Filament\Resources\Variants\RelationManagers\ProductsRelationManager as VariantProductsRelationManager;
use App\Models\Image;
use App\Models\Product;
use App\Models\Tag;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_primary.path')
                    ->label('Основная картинка')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Название')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('tag.name')
                    ->label('Тег')
                    ->placeholder('—'),
                TextColumn::make('price')
                    ->label('Цена')
                    ->money('RUB')
                    ->sortable(),
//                TextColumn::make('variant.name')
//                    ->searchable(),
//                TextColumn::make('type.name')
//                    ->searchable(),
//                TextColumn::make('tag.name')
//                    ->searchable(),
//                TextColumn::make('brand.name')
//                    ->searchable(),
            ])
            ->defaultSort('products.updated_at', 'desc')
            ->recordUrl(
                fn (Product $record): string => ProductResource::getUrl('edit', ['record' => $record]),
                shouldOpenInNewTab: true,
            )
            ->filters([
                SelectFilter::make('tag_id')
                    ->label('Тег')
                    ->multiple()
                    ->searchable()
                    ->options(function ($livewire): array {
                        // В relation manager каталога показываем только теги этого каталога
                        $typeId = match (true) {
                            $livewire instanceof TypeProductsRelationManager => $livewire->getOwnerRecord()->getKey(),
                            $livewire instanceof VariantProductsRelationManager => $livewire->getOwnerRecord()->type_id,
                            default => null,
                        };

                        return Tag::query()
                            ->when($typeId, fn (Builder $query) => $query->where('type_id', $typeId))
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all();
                    })
                    ->hidden(fn ($livewire) => $livewire instanceof TagProductsRelationManager),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ReplicateAction::make()
                    ->excludeAttributes(['video_url', 'views', 'article', 'is_stock', 'is_in_store'])
                    ->beforeReplicaSaved(function (Product $replica, Product $original): void {
                        $replica->name = $original->name . ' ' . str()->uuid();
                        $replica->article = $original->article . ' ' . str()->uuid();

                        if ($original->image_primary_id) $replica->image_primary_id = Image::create($original->image_primary->toArray())->getKey();
                        if ($original->image_secondary_id) $replica->image_secondary_id = Image::create($original->image_secondary->toArray())->getKey();
                    })
                    ->after(function (Product $replica, Product $original): void {
                        $replica->characteristics()->sync($original->characteristics->pluck('id'));

                        foreach ($original->options as $option) {
                            $replicaOption = $option->replicate();
                            if ($option->image_id) $replicaOption->image_id = Image::create($option->image->toArray())->getKey();
                            $replica->options()->save($replicaOption);
                        }
                        foreach ($original->images as $image) {
                            $replicaImage = $image->replicate();
                            if ($image->image_id) $replicaImage->image_id = Image::create($image->image->toArray())->getKey();
                            $replica->images()->save($replicaImage);
                        }

                    }),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
        \Filament\Actions\BulkAction::make('adjust_price')
            ->label('Изменить цену')
            ->icon('heroicon-o-currency-dollar')
            ->form([
                \Filament\Forms\Components\Radio::make('mode')
                    ->label('Режим изменения')
                    ->options([
                        'fixed'   => 'На фиксированную сумму (±N)',
                        'percent' => 'На процент (±N%)',
                    ])
                    ->default('fixed')
                    ->live()
                    ->required(),
                \Filament\Forms\Components\TextInput::make('value')

                    ->label('Значение N')
                    ->helperText('Введите положительное число для увеличения, отрицательное — для уменьшения')
                    ->numeric()
                    ->required(),
            ])
            ->action(function (\Illuminate\Support\Collection $records, array $data): void {
                $records->each(function (Product $record) use ($data): void {
                    $delta = (float) $data['value'];

                    $newPrice = match ($data['mode']) {
                        'fixed'   => $record->price + $delta,
                        'percent' => $record->price * (1 + $delta / 100),
                    };

                    $record->update(['price' => max(0, round($newPrice, 2))]);
                });
            })
->successNotificationTitle('Цены обновлены')
            ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}