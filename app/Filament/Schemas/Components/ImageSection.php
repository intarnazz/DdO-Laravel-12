<?php

namespace App\Filament\Schemas\Components;

use App\Filament\Forms\Components\ImageFieldset;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
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
            Hidden::make('is_image')
                ->disabled()
                ->afterStateHydrated(
                    fn($record, Set $set) => $set('is_image', $record && filled($record->image_id)),
                ),
            ImageFieldset::make()
                ->live()
                ->visible(fn(Get $get) => $get('is_image'))
                ->columnSpanFull(),
            Actions::make([
                Action::make('createImage')
                    ->label('Добавить картинку')
                    ->icon('heroicon-o-plus')
                    ->visible(fn(Get $get) => !$get('is_image'))
                    ->action(fn($livewire, Set $set) => $set('is_image', true)),
                Action::make('deleteImage')
                    ->label('Удалить картинку')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->visible(fn(Get $get) => $get('is_image'))
                    ->requiresConfirmation()
                    ->action(function ($record, Set $set) {
                        $set('is_image', false);
                        if ($record && $record->image) $record->image->delete();
                    })
            ])
        ]);
    }
}
