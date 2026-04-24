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
                    ->disk('public'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('variant.name')
                    ->limit()
                    ->searchable(),
                TextColumn::make('type.name')
                    ->limit()
                    ->searchable(),
                TextColumn::make('tag.name')
                    ->limit()
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->limit()
                    ->searchable(),
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
