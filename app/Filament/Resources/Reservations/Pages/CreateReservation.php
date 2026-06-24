<?php

namespace App\Filament\Resources\Reservations\Pages;

use App\Filament\Resources\Reservations\ReservationResource;
use App\Mail\ReservationConfirmation;
use App\Models\Reservation;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    protected function afterCreate(): void
    {
        /** @var Reservation $reservation */
        $reservation = $this->record;

        $user = $reservation->user;
        /** @var User|null $user */
        $email = $user?->email;
        if ($email) {
            Mail::to($email)->queue(new ReservationConfirmation($reservation));
        }
    }
}
