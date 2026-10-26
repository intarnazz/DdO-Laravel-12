<?php

namespace App\Filament\Resources\Variants\Tables;

use App\Filament\Resources\Variants\VariantResource;
use App\Models\Variant;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
            ])
            ->defaultSort('variants.updated_at', 'desc')
            ->recordUrl(
                fn (Variant $record): string => VariantResource::getUrl('edit', ['record' => $record]),
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