<?php

namespace App\Filament\Resources\Types\RelationManagers;

use App\Filament\Resources\Filters\FilterResourceSub;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class FiltersRelationManager extends RelationManager
{
    protected static string $relationship = 'filters';

    protected static ?string $relatedResource = FilterResourceSub::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
                AttachAction::make()
                    ->preloadRecordSelect()
                ,
            ]);
    }
}
