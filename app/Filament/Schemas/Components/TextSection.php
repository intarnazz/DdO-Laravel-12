<?php

namespace App\Filament\Schemas\Components;

use App\Filament\Forms\Components\TextFieldset;
use App\Models\Text;
use Filament\Actions\Action;
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
            TextFieldset::make()
                ->visible(fn(Get $get) => filled($get('text_id')))
                ->columnSpanFull(),
            Actions::make([
                Action::make('createText')
                    ->label('Добавить текст')
                    ->icon('heroicon-o-plus')
                    ->visible(fn(Get $get) => blank($get('text_id')))
                    ->action(function ($record, $livewire, Set $set) {
                        $text = Text::create(['text' => '///']);
                        $record->text_id = $text->id;
                        $record->save();

                        $record->load('text');

                        if (method_exists($livewire, 'resetTable')) $livewire->resetTable();
                        if (method_exists($livewire, 'refresh')) $livewire->refresh();

                        $set('text_id', $text->id);
                        $set('text.text', $text->text);
                    }),
                Action::make('deleteText')
                    ->label('Удалить текст')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->visible(fn(Get $get) => filled($get('text_id')))
                    ->action(function ($record, $livewire, Set $set) {
                        $record->text?->delete();
                        $record->text_id = null;
                        $record->save();

                        $record->load('text');

                        if (method_exists($livewire, 'resetTable')) $livewire->resetTable();
                        if (method_exists($livewire, 'refresh')) $livewire->refresh();

                        $set('text_id', null);
                        $set('text', null);
                    })
            ]),
        ]);

    }
}
