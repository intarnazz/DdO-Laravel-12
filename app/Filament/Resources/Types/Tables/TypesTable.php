<?php

namespace App\Filament\Resources\Types\Tables;

use Filament\Tables\Table;

class TypesTable extends BaseTypesTable
{
    public static function configure(Table $table): Table
    {
        return parent::configure($table)
            ->modifyQueryUsing(fn($query) => $query->whereNull('self_id'));
    }
}
