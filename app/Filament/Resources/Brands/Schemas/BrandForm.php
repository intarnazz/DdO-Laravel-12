<?php

namespace App\Filament\Resources\Brands\Schemas;

use App\Models\Filter;
use App\Models\Image;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('filter_id')
                    ->default(fn() => Filter::where('type', 'brand')->value('id')),

                Section::make('Фото')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('current_image_preview')
                            ->label('Текущее фото')
                            ->content(fn($record) => $record?->image
                                ? new \Illuminate\Support\HtmlString(
                                    '
        <div class="flex items-center">
            <img
                src="' . asset('storage/' . $record->image->path) . '"
                class="max-h-24 w-auto object-contain rounded-md bg-white p-2 border"
                alt="Brand image"
            >
        </div>
        '
                                )
                                : 'Фото не загружено'
                            )
                            ->visible(fn($record) => $record !== null),

                        FileUpload::make('uploaded_image')
                            ->label('Загрузить новое фото')
                            ->image()
                            ->storeFiles(false)   // важно
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'])
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (is_array($state) && count($state) > 0) {
                                    $state = reset($state);
                                }

                                if ($state instanceof TemporaryUploadedFile) {
                                    $originalName = pathinfo($state->getClientOriginalName(), PATHINFO_FILENAME);
                                    $set('image_name', $originalName ?: (string)Str::uuid());
                                }
                            })
                            ->dehydrated(false),

                        TextInput::make('image_name')
                            ->label('Имя фото')
                            ->nullable()
                            ->unique(
                                table: 'images',
                                column: 'name',
                                ignorable: fn($record) => $record?->image,
                            )
                            ->helperText('По умолчанию — имя загруженного файла'),
                    ]),

                Select::make('text_id')
                    ->label('Текст')
                    ->relationship('text', 'text')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                TextInput::make('name')
                    ->label('Название')
                    ->required(),

                Textarea::make('description')
                    ->label('Описание')
                    ->rows(4)
                    ->required(),
            ]);
    }
}
