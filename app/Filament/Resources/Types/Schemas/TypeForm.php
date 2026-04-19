<?php

namespace App\Filament\Resources\Types\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('self_id')
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('visible_name'),
                TextInput::make('description')
                    ->required(),
            ]);
    }
}
