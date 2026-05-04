<?php

namespace App\Filament\Resources\Characteristics\Tables;

use App\Filament\Resources\Filters\FilterResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CharacteristicsTableDetach extends CharacteristicsTable
{
    public static function configure(Table $table): Table
    {
        return parent::configure($table)
            ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('filter', fn(Builder $query) => $query->where('type', '!=', 'color')))
            ->columns([
                TextColumn::make('name')
                    ->label('название')
                    ->searchable(),
                textColumn::make('description')
                    ->label('Описание')
                    ->limit(80)
                    ->searchable(),
                TextColumn::make('filter.name')
                    ->label('Родительский фильтр')
//                    ->badge()
//                    ->color('primary')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->iconPosition(IconPosition::After)
                    ->searchable()
                    ->url(fn($record) => FilterResource::getUrl('edit', ['record' => $record->filter_id])),
            ])
            ->recordAction(null)
            ->recordActions([
//                EditAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
