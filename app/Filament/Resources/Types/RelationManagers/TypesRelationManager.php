<?php

namespace App\Filament\Resources\Types\RelationManagers;

use App\Filament\Resources\Types\TypeResource;
use App\Filament\Resources\Types\TypeResourceSub;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TypesRelationManager extends RelationManager
{
    protected static string $relationship = 'types';

    protected static ?string $relatedResource = TypeResourceSub::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
