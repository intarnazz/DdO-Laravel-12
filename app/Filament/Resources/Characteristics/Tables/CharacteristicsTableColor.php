<?php

namespace App\Filament\Resources\Characteristics\Tables;

use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CharacteristicsTableColor extends CharacteristicsTable
{
    public static function configure(Table $table): Table
    {
        return parent::configure($table)
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
