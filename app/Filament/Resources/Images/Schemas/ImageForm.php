<?php

namespace App\Filament\Resources\Images\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
                ->live()
                ->afterStateUpdated(function (Set $set, $state) {
                    if (!$state) return;
                    $file = is_array($state) ? collect($state)->first() : $state;

                    if (!($file instanceof TemporaryUploadedFile)) return;
                    $extension = $file->getClientOriginalExtension();
                    $set('type', $extension);


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
