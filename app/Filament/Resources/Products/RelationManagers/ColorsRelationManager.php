<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Characteristics\CharacteristicResourceColorDetach;
use App\Models\Characteristic;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
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
                        ->whereHas('filter', fn(Builder $query) => $query->where('type', 'color'))
                    )
                    ->recordTitle(fn(Characteristic $record): string => "
    <div style='display:flex;align-items:center;gap:8px'>
        <span style='width:16px;height:16px;border-radius:3px;background:{$record->name};
            border:1px solid rgba(0,0,0,.2);display:inline-block;flex-shrink:0'></span>
        <span>{$record->name}</span>
    </div>
")
                    ->recordSelect(fn(Select $select) => $select->allowHtml())
            ]);
    }
}
