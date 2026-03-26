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
        $reservation = $this->record;

        if ($reservation->wasChanged('status') && $reservation->user && $reservation->user->email) {
            Mail::to($reservation->user->email)->queue(new ReservationStatusChanged($reservation));
        }
    }
}
