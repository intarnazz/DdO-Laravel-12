<?php

namespace App\Filament\Resources\Characteristics\Schemas;

use App\Filament\Resources\Filters\FilterResource;
use App\Filament\Schemas\Components\ImageSection;
use Filament\Actions\Action;
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
                Select::make('filter_id')
                    ->relationship('filter', 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->where('name', '!=', 'brand')
                    )
                    ->label('Относиться к фильтру:')
                    ->required()
                    ->suffixAction(
                        Action::make('edit_filter')
                            ->icon('heroicon-o-pencil-square')
                            ->tooltip('Редактировать фильтр')
                            ->url(fn($get) => $get('filter_id')
                                ? FilterResource::getUrl('edit', ['record' => $get('filter_id')])
                                : null
                            )
                            ->openUrlInNewTab()
                    ),
                TextInput::make('name')
                    ->label('Краткое описание')
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
