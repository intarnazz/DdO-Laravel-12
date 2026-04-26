<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
