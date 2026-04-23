<?php

namespace App\Filament\Resources\Types;

use App\Filament\Resources\Types\Tables\TypesTableSubDelete;
use Filament\Tables\Table;

class TypeResourceSub extends TypeResource
{
    public static function table(Table $table): Table
    {
        return TypesTableSubDelete::configure($table);
    }
}
