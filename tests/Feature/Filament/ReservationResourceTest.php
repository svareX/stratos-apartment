<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Illuminate\Support\Facades\Mail;
use App\Models\Apartment;
use App\Models\Reservation;
use App\Models\User;
use App\Filament\Resources\Reservations\Pages\CreateReservation;
use App\Filament\Resources\Reservations\Pages\EditReservation;
use App\Mail\ReservationConfirmation;
use App\Mail\ReservationStatusChanged;
use App\Enums\ReservationStatus;

class ReservationResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_filament_can_create_reservation_and_queue_confirmation(): void
    {
        Mail::fake();

        $apartment = Apartment::create([
            'name_en' => 'Res Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $user = User::factory()->create(['email' => 'res@example.com']);

        $data = [
            'apartment_id' => $apartment->id,
            'user_id' => $user->id,
            'check_in' => now()->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
            'price' => 2000,
            'status' => ReservationStatus::Pending->value,
            'booking_source' => 'local',
        ];

        Livewire::test(CreateReservation::class)
            ->set('data', $data)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('reservations', ['apartment_id' => $apartment->id, 'user_id' => $user->id]);

        Mail::assertQueued(ReservationConfirmation::class, function ($mail) use ($user) {
            $recipient = collect($mail->to)->first();
            if (is_array($recipient)) {
                return ($recipient['address'] ?? null) === $user->email;
            }
            return $recipient?->address === $user->email;
        });
    }

    public function test_filament_can_update_reservation_and_queue_status_changed(): void
    {
        Mail::fake();

        $apartment = Apartment::create([
            'name_en' => 'Res Apt 2',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $user = User::factory()->create(['email' => 'status@example.com']);

        $reservation = Reservation::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user->id,
            'check_in' => now()->toDateString(),
            'check_out' => now()->addDays(1)->toDateString(),
            'price' => 1000,
            'status' => ReservationStatus::Pending->value,
            'booking_source' => 'local',
        ]);

        Livewire::test(EditReservation::class, ['record' => $reservation->id])
            ->set('data', array_merge($reservation->toArray(), ['status' => ReservationStatus::Confirmed->value]))
            ->call('save')
            ->assertHasNoFormErrors();

        Mail::assertQueued(ReservationStatusChanged::class, function ($mail) use ($user) {
            $recipient = collect($mail->to)->first();
            if (is_array($recipient)) {
                return ($recipient['address'] ?? null) === $user->email;
            }
            return $recipient?->address === $user->email;
        });
    }
}
