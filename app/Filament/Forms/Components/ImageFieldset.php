<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Set;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageFieldset extends Fieldset
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->relationship('image');

        $this->schema([
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
        ]);
    }
}
