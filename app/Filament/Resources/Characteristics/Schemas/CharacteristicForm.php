<?php

namespace App\Filament\Resources\Characteristics\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CharacteristicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('filter_id')
                    ->relationship('filter', 'name')
                    ->required(),
                Select::make('image_id')
                    ->relationship('image', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                Toggle::make('is_visible')
                    ->required(),
            ]);
    }
}
