<?php

namespace App\Filament\Resources\Filters\RelationManagers;

use App\Filament\Resources\Characteristics\CharacteristicResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CharacteristicsRelationManager extends RelationManager
{
    protected static string $relationship = 'characteristics';

    protected static ?string $relatedResource = CharacteristicResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
