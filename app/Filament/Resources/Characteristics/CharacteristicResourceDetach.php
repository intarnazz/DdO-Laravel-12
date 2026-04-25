<?php

namespace App\Filament\Resources\Characteristics;

use App\Filament\Resources\Characteristics\Tables\CharacteristicsTableDetach;
use Filament\Tables\Table;

class CharacteristicResourceDetach extends CharacteristicResource
{
    public static function table(Table $table): Table
    {
        return CharacteristicsTableDetach::configure($table);
    }
}
