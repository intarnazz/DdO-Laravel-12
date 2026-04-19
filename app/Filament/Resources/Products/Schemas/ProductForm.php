<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type_id')
                    ->relationship('type', 'name')
                    ->required(),
                Select::make('tag_id')
                    ->relationship('tag', 'name')
                    ->required(),
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
