<?php

namespace App\Filament\Resources\Filters;

use App\Filament\Resources\Filters\Pages\CreateFilter;
use App\Filament\Resources\Filters\Pages\EditFilter;
use App\Filament\Resources\Filters\Pages\ListFilters;
use App\Filament\Resources\Filters\Schemas\FilterForm;
use App\Filament\Resources\Filters\Tables\FiltersTable;
use App\Models\Filter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FilterResource extends Resource
{
    protected static ?string $model = Filter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FilterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FiltersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFilters::route('/'),
            'create' => CreateFilter::route('/create'),
            'edit' => EditFilter::route('/{record}/edit'),
        ];
    }
}
