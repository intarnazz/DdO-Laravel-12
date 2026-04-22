<?php

namespace App\Filament\Resources\Filters\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class FilterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название')
                    ->maxLength(255)
                    ->unique()
                    ->required()
                    ->afterStateUpdated(fn(Get $get, Set $set) => $set('type', $get('name'))),
                Hidden::make('type'),
                Textarea::make('description')
                    ->label('Описание')
                    ->autosize()
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->required(),
            ]);
    }
}
