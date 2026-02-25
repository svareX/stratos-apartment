<?php

namespace App\Filament\Resources\Reservations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use App\Enums\ReservationStatus;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('apartment.name')->label('Apartment')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('user.email')->label('User')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('check_in')->date()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('check_out')->date()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('price')->money('CZK'),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->status?->value ?? '')
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'gray',
                        default => 'secondary',
                    }),
                \Filament\Tables\Columns\TextColumn::make('booking_source')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->schema([
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(ReservationStatus::options())
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->status = $data['status'];
                        $record->save();
                    })
                    ->modalHeading('Update Reservation Status'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->schema([
                            \Filament\Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options(ReservationStatus::options())
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->status = $data['status'];
                                $record->save();
                            }
                        })
                        ->modalHeading('Update Reservation Status for Selected'),
                ]),
            ]);
    }
}
