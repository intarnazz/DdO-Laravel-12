<?php

namespace App\Filament\Schemas\Components;

use App\Filament\Forms\Components\TextFieldset;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class TextSection extends Section
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            Hidden::make('is_text')
                ->disabled()
                ->afterStateHydrated(
                    fn($record, Set $set) => $set('is_text', $record && filled($record->text_id)),
                ),
            TextFieldset::make()
                ->live()
                ->visible(fn(Get $get) => $get('is_text'))
                ->columnSpanFull(),
            Actions::make([
                Action::make('createText')
                    ->label('Добавить текст')
                    ->icon('heroicon-o-plus')
                    ->visible(fn(Get $get) => !$get('is_text'))
                    ->action(fn($livewire, Set $set) => $set('is_text', true)),
                Action::make('deleteText')
                    ->label('Удалить текст')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->visible(fn(Get $get) => $get('is_text'))
                    ->requiresConfirmation()
                    ->action(function ($record, Set $set) {
                        $set('is_text', false);
                        if ($record && $record->text) $record->text->delete();
                    }),
            ]),
        ]);
    }
}
