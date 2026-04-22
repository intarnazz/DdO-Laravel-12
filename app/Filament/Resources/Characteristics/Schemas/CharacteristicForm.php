<?php

namespace App\Filament\Resources\Characteristics\Schemas;

use App\Filament\Resources\Filters\FilterResource;
use App\Filament\Schemas\Components\ImageSection;
use App\Models\Filter;
use Filament\Actions\Action;
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
                    ->columnSpanFull(),
                ColorPicker::make('name')
                    ->label('Выбор цвета:')
                    ->visible(fn(Get $get) => Filter::query()
                        ->where('id', $get('filter_id'))
                        ->where('name', 'color')
                        ->exists()
                    )
                    ->hex()
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule, Get $get) => $rule->where('filter_id', $get('filter_id'))
                    )
                    ->required(),
                TextInput::make('name')
                    ->label('Краткое описание')
                    ->visible(fn(Get $get) => !Filter::query()
                        ->where('id', $get('filter_id'))
                        ->where('name', 'color')
                        ->exists()
                    )
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule, Get $get) => $rule->where('filter_id', $get('filter_id'))
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
