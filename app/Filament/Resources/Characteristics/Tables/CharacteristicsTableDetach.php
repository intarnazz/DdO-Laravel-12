<?php

namespace App\Filament\Resources\Characteristics\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CharacteristicsTableDetach extends CharacteristicsTable
{
    public static function configure(Table $table): Table
    {
        return parent::configure($table)
            ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('filter', fn(Builder $query) => $query->where('type', '!=', 'color')))
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
