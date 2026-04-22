<?php

namespace App\Filament\Schemas\Components;

use App\Filament\Forms\Components\ImageFieldset;
use App\Models\Image;
use Filament\Actions\Action;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class ImageSection extends Section
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            ImageFieldset::make()
                ->visible(fn(Get $get) => filled($get('image_id')))
                ->columnSpanFull(),
            Actions::make([
                Action::make('createImage')
                    ->label('Добавить картинку')
                    ->icon('heroicon-o-plus')
                    ->visible(fn(Get $get) => blank($get('image_id')))
                    ->action(function ($record, $livewire, Set $set) {
                        $image = Image::create([
                            'name' => 'фото по умолчанию',
                            'path' => 'фото-по-умолчанию.jpg',
                        ]);
                        $record->image_id = $image->id;
                        $record->save();

                        $record->load('image');

                        if (method_exists($livewire, 'resetTable')) $livewire->resetTable();
                        if (method_exists($livewire, 'refresh')) $livewire->refresh();

                        $set('image_id', $image->id);
                        $set('image.name', $image->name);
                        $set('image.path', $image->path);

                    }),
                Action::make('deleteImage')
                    ->label('Удалить картинку')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->visible(fn(Get $get) => filled($get('image_id')))
                    ->action(function ($record, $livewire, Set $set) {
                        $record->image?->delete();
                        $record->image_id = null;
                        $record->save();

                        $record->load('image');

                        if (method_exists($livewire, 'resetTable')) $livewire->resetTable();
                        if (method_exists($livewire, 'refresh')) $livewire->refresh();

                        $set('image_id', null);
                        $set('image', null);
                    })
            ]),
        ]);

    }
}
