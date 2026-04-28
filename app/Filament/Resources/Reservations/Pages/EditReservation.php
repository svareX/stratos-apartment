<?php

namespace App\Filament\Resources\Reservations\Pages;

use App\Filament\Resources\Reservations\ReservationResource;
use App\Mail\ReservationStatusChanged;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditReservation extends EditRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        /** @var \App\Models\Reservation $reservation */
        $reservation = $this->record;

        $user = $reservation->user;
        /** @var \App\Models\User|null $user */
        $email = $user?->email;
        if ($reservation->wasChanged('status') && $email) {
            Mail::to($email)->queue(new ReservationStatusChanged($reservation));
        }
    }
}
