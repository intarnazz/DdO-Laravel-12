<?php

namespace App\Filament\Resources\Variants\Schemas;

use App\Filament\Resources\Types\RelationManagers\VariantsRelationManager;
use Filament\Forms\Components\Select;
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
                Select::make('type_id')
                    ->label('Каталог')
                    ->relationship('type', 'name')
                    ->disabled(fn($livewire) => $livewire instanceof VariantsRelationManager)
                    ->default(function ($livewire) {
                        if ($livewire instanceof VariantsRelationManager) return $livewire->getOwnerRecord()->getKey();
                        return null;
                    })
                    ->dehydrated()
                    ->required(),
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
