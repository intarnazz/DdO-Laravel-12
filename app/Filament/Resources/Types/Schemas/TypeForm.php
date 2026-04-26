<?php

namespace App\Filament\Resources\Types\Schemas;

use App\Filament\Resources\Types\TypeResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
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
                    ->suffixAction(
                        Action::make('type')
                            ->icon('heroicon-m-eye')
                            ->visible(fn(Get $get) => filled($get('self_id')))
                            ->url(fn(Get $get) => TypeResource::getUrl('edit', ['record' => $get('self_id')], $get('self_id')))
                            ->openUrlInNewTab())
                ,
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
