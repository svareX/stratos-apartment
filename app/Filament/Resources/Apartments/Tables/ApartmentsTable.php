<?php

namespace App\Filament\Resources\Apartments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),

                IconColumn::make('active')
                    ->label(__('Active'))
                    ->boolean()
                    ->sortable(),

                TextColumn::make('address')
                    ->label(__('Address'))
                    ->sortable(),

                TextColumn::make('capacity')
                    ->label(__('Capacity'))
                    ->sortable(),
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
            ])
            ->filters([
                Filter::make('is_active')
                    ->label(__('Active apartments'))
                    ->query(fn (Builder $query) => $query->where('active', true)),
            ]);
    }
}
