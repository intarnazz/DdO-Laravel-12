<?php

namespace App\Filament\Resources\Images\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ImageForm
{

    public static function getComponents(): array
    {
        return [
            FileUpload::make('path')
                ->maxFiles(1)
                ->label('Изображение')
                ->required()
                ->disk('public')
                ->imagePreviewHeight('500')
                ->columnSpanFull()
                ->afterStateUpdated(function (Set $set, $state) {
                    if ($state) {
                        $extension = pathinfo($state, PATHINFO_EXTENSION);
                        $set('type', $extension);
                    }
                }),
            TextInput::make('name')
                ->label('Имя для фото')
                ->required(),
            Hidden::make('type')
                ->default('img'),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getComponents());
    }
}
