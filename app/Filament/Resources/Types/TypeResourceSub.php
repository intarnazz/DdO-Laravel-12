<?php

namespace App\Filament\Resources\Types;

use App\Filament\Resources\Types\Pages\CreateType;
use App\Filament\Resources\Types\Pages\EditType;
use App\Filament\Resources\Types\Pages\ListTypes;
use App\Filament\Resources\Types\RelationManagers\TypesRelationManager;
use App\Filament\Resources\Types\Schemas\TypeForm;
use App\Filament\Resources\Types\Tables\TypesTable;
use App\Filament\Resources\Types\Tables\TypesTableSub;
use App\Models\Type;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TypeResourceSub extends TypeResource
{
    public static function table(Table $table): Table
    {
        return TypesTableSub::configure($table);
    }
}
