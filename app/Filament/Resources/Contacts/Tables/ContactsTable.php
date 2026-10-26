<?php

namespace App\Filament\Resources\Contacts\Tables;

use App\Filament\Resources\Contacts\ContactResource;
use App\Models\Contact;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('link')
                    ->label('Ссылка')
                    ->limit(80)
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordUrl(
                fn (Contact $record): string => ContactResource::getUrl('edit', ['record' => $record]),
                shouldOpenInNewTab: true,
            )
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}