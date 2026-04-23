<?php

namespace App\Filament\Resources\Filters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
