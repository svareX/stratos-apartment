<?php

namespace App\Filament\Resources\Reservations\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserRelationManager extends RelationManager
{
    protected static string $relationship = 'user';

    public static function getModelLabel(): string
    {
        return __('Client');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Client');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Client');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email')
                    ->label('Email address'),
                TextColumn::make('phone')
                    ->label('Phone'),
                TextColumn::make('address')
                    ->label('Address'),
                TextColumn::make('city')
                    ->label('City'),
                TextColumn::make('postal_code')
                    ->label('Postal code'),
                TextColumn::make('country')
                    ->label('Country'),
            ]);
    }
}
