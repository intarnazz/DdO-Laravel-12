<?php

namespace App\Filament\Resources\Filters\RelationManagers;

use App\Filament\Resources\Types\TypeResourceSub;
use App\Filament\Resources\Types\TypeResourceSubDetach;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TypesRelationManager extends RelationManager
{
    protected static string $relationship = 'types';

    protected static ?string $relatedResource = TypeResourceSubDetach::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
                AttachAction::make()
                    ->preloadRecordSelect()
            ]);
    }
}
