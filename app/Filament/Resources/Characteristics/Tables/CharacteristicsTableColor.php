<?php

namespace App\Filament\Resources\Characteristics\Tables;

use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CharacteristicsTableColor extends CharacteristicsTable
{
    public static function configure(Table $table): Table
    {
        return parent::configure($table)
            ->modifyQueryUsing(fn(Builder $query) => $query->whereHas(
                'filter',
                fn(Builder $query) => $query->where('type', 'color')))
            ->columns([
                ColorColumn::make('name')
                    ->label('Цвет'),
                textColumn::make('description')
                    ->label('Описание')
                    ->limit()
                    ->searchable(),
            ]);
    }
}
