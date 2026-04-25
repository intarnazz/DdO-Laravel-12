<?php

namespace App\Filament\Resources\Options\Schemas;

use App\Filament\Resources\Products\RelationManagers\OptionsRelationManager as ProductOptionsRelationManager;
use App\Filament\Schemas\Components\ImageSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class OptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageSection::make('Фото опции')
                    ->columnSpanFull(),
                Select::make('product_id')
                    ->label('Отношение к продукту:')
                    ->relationship('product', 'name')
                    ->default(
                        function ($livewire) {
                            if ($livewire instanceof ProductOptionsRelationManager) return $livewire->getOwnerRecord()->getKey();
                            return null;
                        }
                    )
                    ->disabled(
                        function ($livewire) {
                            if ($livewire instanceof ProductOptionsRelationManager) return $livewire->getOwnerRecord()->getKey();
                            return null;
                        }
                    )
                    ->dehydrated()
                    ->required(),
                TextInput::make('name')
                    ->label('Название')
                    ->maxLength(255)
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule, Get $get) => $rule->where('product_id', $get('product_id'))
                    )
                    ->required(),
                TextInput::make('price')
                    ->minValue(0)
                    ->maxValue(1_000_000_000)
                    ->label('Цена')
                    ->required()
                    ->numeric()
                    ->prefix('₽'),
                Toggle::make('is_set')
                    ->label('Комплект / Цена указывается за комплект')
                    ->required(),
                TextInput::make('default_count')
                    ->label('Базовое число комплектации / Установите 0 если не входит в комплект')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0)
                    ->required(),
                Textarea::make('description')
                    ->label('Описание')
                    ->autosize()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}
