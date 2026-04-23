<?php

namespace App\Filament\Resources\Types;

use App\Filament\Resources\Types\Tables\TypesTableSubDetach;
use Filament\Tables\Table;

class TypeResourceSubDetach extends TypeResourceSub
{
    public static function table(Table $table): Table
    {
        return TypesTableSubDetach::configure($table);
    }
}
