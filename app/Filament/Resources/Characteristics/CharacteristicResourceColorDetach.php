<?php

namespace App\Filament\Resources\Characteristics;

use App\Filament\Resources\Characteristics\Tables\CharacteristicsTableColorDetach;
use Filament\Tables\Table;

class CharacteristicResourceColorDetach extends CharacteristicResource
{
//    protected static ?string $recordTitleAttribute = 'description';

    public static function table(Table $table): Table
    {
        return CharacteristicsTableColorDetach::configure($table);
    }
}
