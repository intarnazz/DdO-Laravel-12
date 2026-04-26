<?php

namespace App\Filament\Resources\Products;

use App\Filament\Clusters\Type\TypeCluster;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\RelationManagers\CharacteristicsRelationManager;
use App\Filament\Resources\Products\RelationManagers\ColorsRelationManager;
use App\Filament\Resources\Products\RelationManagers\GalleriesRelationManager;
use App\Filament\Resources\Products\RelationManagers\OptionsRelationManager;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'Продукт';
    protected static ?string $pluralModelLabel = 'Продукты';

    protected static ?string $cluster = TypeCluster::class;

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ColorsRelationManager::make(),
            CharacteristicsRelationManager::make(),
            OptionsRelationManager::make(),
            GalleriesRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
