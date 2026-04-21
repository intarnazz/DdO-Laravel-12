<?php

namespace App\Filament\Resources\Brands\Schemas;

use App\Filament\Forms\Components\ImageFieldset;
use App\Filament\Schemas\Components\TextSection;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageFieldset::make('Логотип бренда')
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label('Имя бренда')
                    ->unique()
                    ->maxLength(255)
                    ->required(),
                Textarea::make('description')
                    ->label('Описание')
                    ->autosize()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextSection::make('Текст бренда')
                    ->columnSpanFull(),
            ]);
    }
}
