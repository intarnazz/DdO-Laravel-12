<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Characteristics\CharacteristicResourceColorDetach;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ColorsRelationManager extends RelationManager
{
    protected static string $relationship = 'characteristics';

    protected static ?string $relatedResource = CharacteristicResourceColorDetach::class;

    protected static ?string $title = 'Цвета';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn(Builder $query) => $query
                        ->whereHas('filter.types', function (Builder $query) {
                            $query->where('types.id', $this->getOwnerRecord()->type_id);
                        })
                        ->whereHas('filter', fn(Builder $query) => $query->where('name', 'color'))
                    )
            ]);
    }
}
