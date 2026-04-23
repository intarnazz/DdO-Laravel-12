<?php

namespace App\Filament\Resources\Filters\Tables;

use App\Models\TypeFilter;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class FiltersTableSub extends BaseFiltersTable
{
    public static function configure(Table $table): Table
    {
        return parent::configure($table)
            ->recordActions([
                EditAction::make(),
                DetachAction::make()
            ]);
    }
}
