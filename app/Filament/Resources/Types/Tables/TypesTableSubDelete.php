<?php

namespace App\Filament\Resources\Types\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class TypesTableSubDelete extends TypesTableSub
{
    public static function configure(Table $table): Table
    {
        return parent::configure($table)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
