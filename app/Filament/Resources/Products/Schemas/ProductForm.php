<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Forms\Components\ImageFieldset;
use App\Filament\Resources\Tags\TagResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageFieldset::make('Фото')
                    ->relationship('image_primary')
                    ->columnSpanFull(),
                Select::make('type_id')
                    ->relationship('type', 'name')
                    ->required(), // TODO: sub
                Select::make('tag_id')
                    ->relationship('tag', 'name')
                    ->suffixAction(
                        Action::make('tag')
                            ->visible(fn(Get $get) => filled($get('tag_id')))
                            ->icon('heroicon-m-eye')
                            ->url(fn(Get $get) => TagResource::getUrl('edit', ['record' => $get('tag_id')], $get('tag_id')))
                            ->openUrlInNewTab()),
                Select::make('variant_id')
                    ->relationship('variant', 'name')
                    ->required(),
                Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->required(),
                Select::make('image_primary_id')
                    ->relationship('image_primary', 'name'),
                Select::make('image_secondary_id')
                    ->relationship('image_secondary', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('video_url')
                    ->url(),
                TextInput::make('views')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('article')
                    ->required(),
                Toggle::make('is_stock')
                    ->required(),
                Toggle::make('is_in_store')
                    ->required(),
            ]);
    }
}
