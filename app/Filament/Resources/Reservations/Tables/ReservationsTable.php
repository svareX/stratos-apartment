<?php

namespace App\Filament\Resources\Reservations\Tables;

use App\Enums\ReservationStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('apartment.name')->label(__('Apartment'))->sortable(),
                \Filament\Tables\Columns\TextColumn::make('user.email')->label(__('User'))->sortable(),
                \Filament\Tables\Columns\TextColumn::make('check_in')->label(__('Check in'))->date()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('check_out')->label(__('Check out'))->date()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('price')->label(__('Price'))->money('CZK'),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->status?->value ?? '')
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'gray',
                        default => 'secondary',
                    }),
                \Filament\Tables\Columns\TextColumn::make('booking_source')->label(__('Booking Source'))->sortable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label(__('Created at'))->dateTime()->sortable(),
            ])
            ->filters([
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('updateStatus')
                    ->label(__('Update Status'))
                    ->icon('heroicon-o-arrow-path')
                    ->schema([
                        \Filament\Forms\Components\Select::make('status')
                            ->label(__('Status'))
                            ->options(ReservationStatus::options())
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->status = $data['status'];
                        $record->save();
                    })
                    ->modalHeading(__('Update Reservation Status')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('updateStatus')
                        ->label(__('Update Status'))
                        ->icon('heroicon-o-arrow-path')
                        ->schema([
                            \Filament\Forms\Components\Select::make('status')
                                ->label(__('Status'))
                                ->options(ReservationStatus::options())
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->status = $data['status'];
                                $record->save();
                            }
                        })
                        ->modalHeading(__('Update Reservation Status for Selected')),
                ]),
            ]);
    }
}
