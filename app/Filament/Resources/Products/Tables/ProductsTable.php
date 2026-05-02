<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Image;
use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ->searchable(),
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
            ->filters([
                //
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
                ]),
            ]);
    }
}
