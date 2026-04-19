<?php

namespace App\Filament\Resources\Filters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FilterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('type')
                    ->required(),
            ]);
    }
}
