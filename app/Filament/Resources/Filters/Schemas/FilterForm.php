<?php

namespace App\Filament\Resources\Filters\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FilterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название')
                    ->disabled(function ($record) {
                        if ($record && ($record->disable_edit == true)) return true;
                        return false;
                    })
                    ->maxLength(255)
                    ->unique()
                    ->required()
                    ->afterStateUpdated(fn(Get $get, Set $set) => $set('type', Str::slug($get('name')))),
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
