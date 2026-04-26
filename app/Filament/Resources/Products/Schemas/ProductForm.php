<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Forms\Components\ImageFieldset;
use App\Filament\Resources\Brands\RelationManagers\ProductsRelationManager as BrandProductsRelationManager;
use App\Filament\Resources\Tags\RelationManagers\ProductsRelationManager as TagProductsRelationManager;
use App\Filament\Resources\Types\RelationManagers\ProductsRelationManager as TypeProductsRelationManager;
use App\Filament\Resources\Variants\RelationManagers\ProductsRelationManager as VariantProductsRelationManager;
use App\Models\Tag;
use App\Models\Type;
use App\Models\Variant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageFieldset::make('Основное фото')
                    ->relationship('image_primary')
                    ->columnSpan(
                        function (Get $get) {
                            $type = Type::find($get('type_id'));
                            if ($type && $type->slug === 'entrance-doors') return null;
                            return 'full';
                        }
                    ),
                ImageFieldset::make('Фото внутреннего полотна двери')
                    ->visible(
                        function (Get $get) {
                            $type = Type::find($get('type_id'));
                            if ($type) return $type->slug === 'entrance-doors';
                            return false;
                        }
                    )
                    ->relationship('image_secondary'),
                Section::make()
                    ->schema([
                        Select::make('type_id')
                            ->label('Каталог')
                            ->relationship('type', 'name')
                            ->live()
                            ->disabled(fn($livewire) => $livewire instanceof TypeProductsRelationManager
                                || $livewire instanceof TagProductsRelationManager
                                || $livewire instanceof VariantProductsRelationManager
                            )
                            ->dehydrated()
                            ->default(function ($livewire) {
                                if ($livewire instanceof TypeProductsRelationManager) return $livewire->getOwnerRecord()->getKey();
                                if ($livewire instanceof RelationManager) return $livewire->getOwnerRecord()->type_id;
                                return null;
                            })
                            ->required(),
                        Select::make('tag_id')
                            ->label('Тег')
                            ->relationship('tag', 'name')
                            ->disabled(fn($livewire, Get $get) => $livewire instanceof TagProductsRelationManager || blank($get('type_id')))
                            ->dehydrated()
                            ->options(
                                fn(Get $get) => Tag::where(
                                    'type_id',
                                    $get('type_id'))->pluck('name', 'id')
                            )
                            ->default(function ($livewire) {
                                if ($livewire instanceof TagProductsRelationManager) return $livewire->getOwnerRecord()->getKey();
                                return null;
                            }),
                        Select::make('variant_id')
                            ->label('Модель')
                            ->relationship('variant', 'name')
                            ->disabled(fn($livewire, Get $get) => $livewire instanceof VariantProductsRelationManager || blank($get('type_id')))
                            ->dehydrated()
                            ->options(
                                fn(Get $get) => Variant::where(
                                    'type_id',
                                    $get('type_id'))->pluck('name', 'id')
                            )
                            ->default(function ($livewire) {
                                if ($livewire instanceof VariantProductsRelationManager) return $livewire->getOwnerRecord()->getKey();
                                return null;
                            })
                            ->required(),
                    ])
                    ->columnSpanFull(),

                Select::make('brand_id')
                    ->label('Бренд')
                    ->relationship('brand', 'name')
                    ->disabled(fn($livewire) => $livewire instanceof BrandProductsRelationManager)
                    ->dehydrated()
                    ->default(function ($livewire) {
                        if ($livewire instanceof BrandProductsRelationManager) return $livewire->getOwnerRecord()->getKey();
                        return null;
                    })
                    ->required(),
                TextInput::make('name')
                    ->label('Название')
                    ->maxLength(255)
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule, Get $get) => $rule->where('variant_id', $get('variant_id'))
                    )
                    ->required(),
                TextInput::make('video_url')
                    ->label('Ссылка на видео')
                    ->maxLength(255)
                    ->url(),
                TextInput::make('price')
                    ->minValue(0)
                    ->maxValue(1_000_000_000)
                    ->label('Цена')
                    ->required()
                    ->numeric()
                    ->prefix('₽'),
                TextInput::make('discount')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->label('Скидка')
                    ->numeric()
                    ->prefix('%')
                    ->default(0),
                TextInput::make('article')
                    ->label('Артикль')
                    ->maxLength(255)
                    ->required(),
                Toggle::make('is_stock')
                    ->label('В наличии к покупке'),
                Toggle::make('is_in_store')
                    ->label('Присутствует в магазине'),
                Textarea::make('description')
                    ->label('Описание')
                    ->maxLength(255)
                    ->autosize()
                    ->columnSpanFull(),
            ]);
    }
}
