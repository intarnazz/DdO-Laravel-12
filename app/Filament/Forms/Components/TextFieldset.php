<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;

class TextFieldset extends Fieldset
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->relationship('text');

        $this->schema([
            Textarea::make('text')
                ->label('Текст')
                ->autosize()
                ->columnSpanFull()
                ->required(),
        ]);
    }
}
