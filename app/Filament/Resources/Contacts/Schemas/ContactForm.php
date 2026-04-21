<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('name')
                    ->label('Тип')
                    ->options([
                        'email' => 'Почта',
                        'phone' => 'Телефон',
                        'url' => 'URL',
                        'other' => 'Другое (своё)',
                    ])
                    ->required()
                    ->live()
                    ->searchable()
                    ->afterStateHydrated(function (Get $get, Set $set) {
                        $value = $get('name');
                        if ($value && !in_array($value, ['email', 'phone', 'url'])) {
                            $set('name', 'other');
                            $set('custom_name', $value);
                        }
                    })
                    ->dehydrateStateUsing(fn(Get $get) => $get('name') === 'other'
                        ? $get('custom_name')
                        : $get('name')
                    ),

                TextInput::make('custom_name')
                    ->label('Свой тип контакта')
                    ->visible(fn(Get $get) => $get('name') === 'other')
                    ->required(fn(Get $get) => $get('name') === 'other')
                    ->maxLength(255)
                    ->live(),

                TextInput::make('link')
                    ->label('Данные для контакта (Телефон, почта, ссылка)')
                    ->required()
                    ->maxLength(255)
                    ->live()
                    ->rules(function (Get $get) {
                        $type = $get('name') === 'other'
                            ? $get('custom_name')
                            : $get('name');

                        return match ($type) {
                            'email' => ['email'],
                            'url' => ['url'],
                            'phone' => ['regex:/^(\+7|8)?[\s\-\(\)]*\d[\s\-\(\)]*\d{9,}$/'],
                            default => ['string'],
                        };
                    }),

                Textarea::make('description')
                    ->label('Тестовое описание')
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
