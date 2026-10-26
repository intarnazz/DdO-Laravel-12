<?php

namespace App\Filament\Resources\Tags\Tables;

use App\Filament\Resources\Tags\TagResource;
use App\Models\Tag;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TagsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
            ])
            ->defaultSort('tags.updated_at', 'desc')
            ->recordUrl(
                fn (Tag $record): string => TagResource::getUrl('edit', ['record' => $record]),
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