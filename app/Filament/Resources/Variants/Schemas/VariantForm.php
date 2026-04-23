<?php

namespace App\Filament\Resources\Variants\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class VariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название')
                    ->maxLength(255)
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule, RelationManager $livewire) => $rule->where('type_id', $livewire->getOwnerRecord()->getKey())
                    )
                    ->required(),
                Textarea::make('description')
                    ->label('Описание')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->autosize()

                ,
            ]);
    }
}
