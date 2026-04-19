<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                Select::make('type')
                    ->options(['email' => 'Email', 'phone' => 'Phone', 'url' => 'Url'])
                    ->default('url')
                    ->required(),
                TextInput::make('link')
                    ->required(),
            ]);
    }
}
