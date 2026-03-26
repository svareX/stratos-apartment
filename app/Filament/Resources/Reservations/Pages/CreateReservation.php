<?php

namespace App\Filament\Resources\Reservations\Pages;

use App\Filament\Resources\Reservations\ReservationResource;
use App\Mail\ReservationConfirmation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    protected function afterCreate(): void
    {
        $reservation = $this->record;

        if ($reservation->user && $reservation->user->email) {
            Mail::to($reservation->user->email)->queue(new ReservationConfirmation($reservation));
        }
    }
}
