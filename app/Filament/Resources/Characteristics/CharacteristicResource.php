<?php

namespace App\Filament\Resources\Characteristics;

use App\Filament\Resources\Characteristics\Pages\CreateCharacteristic;
use App\Filament\Resources\Characteristics\Pages\EditCharacteristic;
use App\Filament\Resources\Characteristics\Pages\ListCharacteristics;
use App\Filament\Resources\Characteristics\Schemas\CharacteristicForm;
use App\Filament\Resources\Characteristics\Tables\CharacteristicsTable;
use App\Filament\Resources\Characteristics\Tables\CharacteristicsTableColor;
use App\Models\Characteristic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CharacteristicResource extends Resource
{
    protected static ?string $model = Characteristic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CharacteristicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        $record = $table->getLivewire()->getOwnerRecord();

        if ($record->name === 'color') return CharacteristicsTableColor::configure($table);
        return CharacteristicsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
}
