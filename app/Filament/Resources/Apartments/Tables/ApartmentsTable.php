<?php

namespace App\Filament\Resources\Apartments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ApartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')->label(__('Name'))->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('address')->label(__('Address'))->sortable(),
                \Filament\Tables\Columns\TextColumn::make('capacity')->label(__('Capacity')),
                \Filament\Tables\Columns\TextColumn::make('base_price')->label(__('Base price'))->money('CZK'),
                \Filament\Tables\Columns\IconColumn::make('active')->label(__('Active'))->boolean(),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label(__('Created at'))->dateTime()->sortable(),
            ])
            ->filters([
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}