<?php

namespace App\Filament\Resources\Reservations\Tables;

use App\Enums\BookingSource;
use App\Enums\ReservationStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->status?->value ?? '')
                    ->formatStateUsing(fn ($state) => ReservationStatus::from($state)->label() ?? '')
                    ->color(fn ($state) => match ($state) {
                        ReservationStatus::Pending->value => 'warning',
                        ReservationStatus::Confirmed->value => 'info',
                        ReservationStatus::Cancelled->value => 'danger',
                        ReservationStatus::Completed->value => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('apartment.name')
                    ->label(__('Apartment'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.email')
                    ->label(__('User'))
                    ->sortable(),

                TextColumn::make('check_in')
                    ->label(__('Check in'))
                    ->date($format = 'd.m.Y')
                    ->sortable(),

                TextColumn::make('check_out')
                    ->label(__('Check out'))
                    ->date($format = 'd.m.Y')
                    ->sortable(),

                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('CZK')
                    ->sortable(),

                TextColumn::make('booking_source')
                    ->label(__('Booking Source'))
                    ->formatStateUsing(fn ($state) => BookingSource::from($state)->label() ?? '')
                    ->sortable(),
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
                    ->modalHeading(__('Update Reservation Status'))
                    ->successNotificationTitle(__('Status Updated Successfully'))
                    ->failureNotificationTitle(__('Failed to Update Status')),
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
                        ->modalHeading(__('Update Reservation Status for Selected'))
                        ->successNotificationTitle(__('Status Updated Successfully'))
                        ->failureNotificationTitle(__('Failed to Update Status')),
                ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(ReservationStatus::options()),

                SelectFilter::make('booking_source')
                    ->label(__('Booking Source'))
                    ->options(BookingSource::options()),

                Filter::make('check_in')
                    ->label(__('Check-in period'))
                    ->schema([
                        DatePicker::make('from')->label(__('Check-in from')),
                        DatePicker::make('to')->label(__('Check-in to')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('check_in', '>=', $date))
                            ->when($data['to'], fn (Builder $query, $date): Builder => $query->whereDate('check_in', '<=', $date));
                    }),

                Filter::make('check_out')
                    ->label(__('Check-out period'))
                    ->schema([
                        DatePicker::make('from')->label(__('Check-out from')),
                        DatePicker::make('to')->label(__('Check-out to')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('check_out', '>=', $date))
                            ->when($data['to'], fn (Builder $query, $date): Builder => $query->whereDate('check_out', '<=', $date));
                    }),
            ]);
    }
}
