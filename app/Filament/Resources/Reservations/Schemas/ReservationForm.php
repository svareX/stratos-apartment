<?php

namespace App\Filament\Resources\Reservations\Schemas;

use App\Enums\BookingSource;
use App\Enums\ReservationStatus;
use App\Models\Apartment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('apartment_id')
                    ->label(__('Apartment'))
                    ->options(fn () => Apartment::all()->mapWithKeys(fn ($a) => [$a->id => $a->name])->toArray())
                    ->preload()
                    ->searchable()
                    ->required(),

                Select::make('user_id')
                    ->label(__('User'))
                    ->relationship('user', 'email')
                    ->preload()
                    ->searchable()
                    ->nullable(),

                DatePicker::make('check_in')
                    ->label(__('Check in'))
                    ->required(),

                DatePicker::make('check_out')
                    ->label(__('Check out'))
                    ->required(),

                TextInput::make('price')
                    ->label(__('Price'))
                    ->placeholder(__('Enter total price for the reservation'))
                    ->numeric()
                    ->suffix(__('CZK'))
                    ->required(),

                Select::make('status')
                    ->label(__('Status'))
                    ->options(ReservationStatus::options())
                    ->default(ReservationStatus::Pending->value)
                    ->searchable()
                    ->required(),

                Select::make('booking_source')
                    ->label(__('Booking Source'))
                    ->options(BookingSource::options())
                    ->default(BookingSource::Local->value)
                    ->required()
                    ->reactive(),

                TextInput::make('external_booking_id')
                    ->label(__('External booking id'))
                    ->placeholder(__('Enter booking id from external system'))
                    ->nullable()
                    ->visible(fn ($get) => $get('booking_source') === BookingSource::External->value),
            ]);
    }
}
