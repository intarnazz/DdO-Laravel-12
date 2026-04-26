<?php

namespace App\Filament\Resources\Types;

use App\Filament\Resources\Types\Pages\CreateType;
use App\Filament\Resources\Types\Pages\EditType;
use App\Filament\Resources\Types\Pages\ListTypes;
use App\Filament\Resources\Types\RelationManagers\FiltersRelationManager;
use App\Filament\Resources\Types\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\Types\RelationManagers\TagsRelationManager;
use App\Filament\Resources\Types\RelationManagers\TypesRelationManager;
use App\Filament\Resources\Types\RelationManagers\VariantsRelationManager;
use App\Filament\Resources\Types\Schemas\TypeForm;
use App\Filament\Resources\Types\Tables\TypesTable;
use App\Models\Type;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TypeResource extends Resource
{
    protected static ?string $model = Type::class;

    protected static ?string $modelLabel = 'Каталог';
    protected static ?string $pluralModelLabel = 'Каталоги';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::make(),
            VariantsRelationManager::make(),
            TagsRelationManager::make(),
            FiltersRelationManager::make(),
            TypesRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTypes::route('/'),
            'create' => CreateType::route('/create'),
            'edit' => EditType::route('/{record}/edit'),
        ];
    }
}
