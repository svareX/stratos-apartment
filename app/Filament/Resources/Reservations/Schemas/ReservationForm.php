<?php

namespace App\Filament\Resources\Reservations\Schemas;

use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('apartment_id')
                    ->label('Apartment')
                    ->relationship('apartment', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'email')
                    ->preload()
                    ->searchable()
                    ->nullable(),
                \Filament\Forms\Components\DatePicker::make('check_in')->required(),
                \Filament\Forms\Components\DatePicker::make('check_out')->required(),
                \Filament\Forms\Components\TextInput::make('price')->numeric()->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->options(\App\Enums\ReservationStatus::options())
                    ->default(\App\Enums\ReservationStatus::Pending->value)
                    ->searchable()
                    ->required(),
                \Filament\Forms\Components\Select::make('booking_source')
                    ->label('Booking Source')
                    ->options(\App\Enums\BookingSource::options())
                    ->default(\App\Enums\BookingSource::Local->value)
                    ->required()
                    ->reactive(),
                \Filament\Forms\Components\TextInput::make('external_booking_id')
                    ->nullable()
                    ->visible(fn ($get) => $get('booking_source') === \App\Enums\BookingSource::External->value),
            ]);
    }
}
