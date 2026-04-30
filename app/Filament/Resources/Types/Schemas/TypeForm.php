<?php

namespace App\Filament\Resources\Types\Schemas;

use App\Filament\Resources\Types\RelationManagers\TypesRelationManager as TypeTypesRelationManager;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('self_id')
                    ->label('Родительский каталог')
                    ->relationship('type', 'name')
                    ->live()
                    ->disabled(fn($livewire) => $livewire instanceof TypeTypesRelationManager)
                    ->dehydrated()
                    ->default(function ($livewire) {
                        if ($livewire instanceof TypeTypesRelationManager) return $livewire->getOwnerRecord()->getKey();
                        return null;
                    })
                    ->required(),
                TextInput::make('name')
                    ->label('Название')
                    ->maxLength(255)
                    ->unique()
                    ->required(),
                Textarea::make('description')
                    ->label('Описание')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->autosize()
                    ->required(),
            ]);
    }
}
