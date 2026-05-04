<?php

namespace App\Filament\Resources\Characteristics\Schemas;

use App\Filament\Resources\Filters\RelationManagers\CharacteristicsRelationManager as FilterCharacteristicsRelationManager;
use App\Filament\Resources\Products\RelationManagers\CharacteristicsRelationManager as ProductsCharacteristicsRelationManager;
use App\Filament\Resources\Products\RelationManagers\ColorsRelationManager as ProductsColorsRelationManager;
use App\Filament\Schemas\Components\ImageSection;
use App\Models\Filter;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;

class CharacteristicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageSection::make('Фото характеристики')
                    ->hidden(
                        fn($livewire) => $livewire instanceof ProductsColorsRelationManager
                    )
                    ->columnSpanFull(),
                Select::make('filter_id')
                    ->label('Фильтр')
                    ->relationship('filter', 'name', function (Builder $query, $livewire) {
                        $query->where('name', '!=', 'brand');
                        if ($livewire instanceof ProductsCharacteristicsRelationManager) $query->where('type', '!=', 'color');
                    }
                    )
                    ->live()
                    ->disabled(fn($record, $livewire) => filled($record)
                        || $livewire instanceof ProductsColorsRelationManager
                        || $livewire instanceof FilterCharacteristicsRelationManager
                    )
                    ->dehydrated()
                    ->default(function ($livewire) {
                        if ($livewire instanceof FilterCharacteristicsRelationManager) return $livewire->getOwnerRecord()->getKey();
                        if ($livewire instanceof ProductsColorsRelationManager) return Filter::where('type', 'color')->first()->id;
                        return null;
                    })
                    ->required(),
                ColorPicker::make('name')
                    ->label('Выбор цвета:')
                    ->visible(fn(Get $get) => Filter::query()
                        ->where('id', $get('filter_id'))
                        ->where('type', 'color')
                        ->exists()
                    )
                    ->hex()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule, Get $get) {
                            return $rule->where('filter_id', $get('filter_id'));
                        }
                    )
                    ->required(),
                TextInput::make('name')
                    ->label('Краткое описание')
                    ->visible(fn(Get $get) => !Filter::query()
                        ->where('id', $get('filter_id'))
                        ->where('type', 'color')
                        ->exists()
                    )
                    ->unique(
                        modifyRuleUsing: function (Unique $rule, Get $get) {
                            return $rule->where('filter_id', $get('filter_id'));
                        }
                    )
                    ->maxLength(255)
                    ->required(),
                Textarea::make('description')
                    ->label('Полное описание')
                    ->maxLength(255)
                    ->autosize()
                    ->columnSpanFull(),
            ]);
    }
}
