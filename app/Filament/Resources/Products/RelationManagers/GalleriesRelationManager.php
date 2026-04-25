<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Images\ImageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class GalleriesRelationManager extends RelationManager
{
    protected static string $relationship = 'galleries';

    protected static ?string $relatedResource = ImageResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
