<?php

namespace App\Filament\Resources\Options\Schemas;

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
                Select::make('product_id') // TODO: дочерний
                ->label('Отношение к продукту:')
                    ->relationship('product', 'name')
                    ->required(),
                TextInput::make('name')
                    ->label('Название')
                    ->maxLength(255)
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule, Get $get) => $rule->where('product_id', $get('product_id'))
                    )
                    ->required(),
                TextInput::make('price')
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
                    ->default(0),
                Textarea::make('description')
                    ->label('Описание')
                    ->autosize()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}
