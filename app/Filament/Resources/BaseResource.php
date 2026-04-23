<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Filters\Pages\CreateFilter;
use App\Filament\Resources\Filters\Pages\EditFilter;
use App\Filament\Resources\Filters\Pages\ListFilters;
use App\Filament\Resources\Filters\RelationManagers\CharacteristicsRelationManager;
use App\Filament\Resources\Filters\Schemas\FilterForm;
use App\Filament\Resources\Filters\Tables\FiltersTable;
use App\Models\Filter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BaseResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'name';
}
