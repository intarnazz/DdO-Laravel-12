<?php

namespace App\Filament\Resources\Types\Tables;

use Filament\Tables\Table;

class TypesTableSub extends BaseTypesTable
{
    public static function configure(Table $table): Table
    {
        return parent::configure($table);
    }
}
