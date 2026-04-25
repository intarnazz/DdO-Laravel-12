<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Options\OptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class OptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';

    protected static ?string $relatedResource = OptionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
