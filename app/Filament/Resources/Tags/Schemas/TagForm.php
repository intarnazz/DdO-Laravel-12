<?php

namespace App\Filament\Resources\Tags\Schemas;

use App\Filament\Resources\Types\RelationManagers\TagsRelationManager;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type_id')
                    ->label('Каталог')
                    ->relationship('type', 'name')
                    ->disabled(fn($livewire) => $livewire instanceof TagsRelationManager)
                    ->dehydrated()
                    ->default(function ($livewire) {
                        if ($livewire instanceof TagsRelationManager) return $livewire->getOwnerRecord()->getKey();
                        return null;
                    })
                    ->required(),
                TextInput::make('name')
                    ->label('Название')
                    ->maxLength(255)
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule, Get $get) => $rule->where('type_id', $get('type_id'))
                    )
                    ->required(),
                Textarea::make('description')
                    ->label('Описание')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->autosize(),
            ]);
    }
}
