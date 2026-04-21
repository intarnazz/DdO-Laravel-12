<?php

namespace App\Filament\Resources\Filters\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                    ->required(),
                TextInput::make('type')
                    ->label('латинское название ( только латинские буквы, без цифр и пробелов)')
                    ->maxLength(255)
                    ->rule('regex:/^[a-zA-Z]+$/')
                    ->unique()
                    ->required(),
                Textarea::make('description')
                    ->label('Описание')
                    ->autosize()
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->required(),
            ]);
    }
}
