<?php

namespace App\Filament\Resources\Brands\Tables;

use App\Filament\Resources\Brands\BrandResource;
use App\Models\Brand;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image.path')
                    ->label('Лого')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label("Название")
                    ->searchable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordUrl(
                fn (Brand $record): string => BrandResource::getUrl('edit', ['record' => $record]),
                shouldOpenInNewTab: true,
            )
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