<?php

namespace App\Filament\Schemas\Components;

use App\Filament\Forms\Components\TextFieldset;
use App\Models\Text;
use Filament\Actions\Action;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class TextSection extends Section
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            TextFieldset::make()
                ->visible(fn(Get $get) => filled($get('text_id')))
                ->columnSpanFull(),
            Actions::make([
                Action::make('createText')
                    ->label('Добавить текст')
                    ->icon('heroicon-o-plus')
                    ->visible(fn(Get $get) => blank($get('text_id')))
                    ->action(function ($record, $livewire) {
                        $text = Text::create(['text' => '///']);
                        $record->text_id = $text->id;
                        $record->save();

                        $record->load('text');

                        $livewire->refresh();
                        $livewire->refreshFormData(['text_id', 'text']);
                    }),
                Action::make('deleteText')
                    ->label('Удалить текст')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->visible(fn(Get $get) => filled($get('text_id')))
                    ->action(function ($record, $livewire) {
                        $record->text?->delete();
                        $record->text_id = null;
                        $record->save();

                        $record->load('text');

                        $livewire->refresh();
                        $livewire->refreshFormData(['text_id', 'text']);
                    })
            ]),
        ]);

    }
}
