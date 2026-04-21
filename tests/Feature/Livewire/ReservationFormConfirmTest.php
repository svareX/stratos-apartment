<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Mail\ReservationConfirmation;
use App\Models\Apartment;
use App\Models\ApartmentPackage;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationFormConfirmTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_creates_reservation_and_queues_email_and_sets_session(): void
    {
        Mail::fake();

        $apt = Apartment::create([
            'name_en' => 'Confirm Apt',
            'address' => 'Addr',
            'capacity' => 4,
            'base_price' => 800,
            'active' => true,
        ]);

        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Dinner',
            'price' => 150.0,
            'icon' => '🍽️',
        ]);

        $email = 'jane@gmail.com';

        Livewire::test(\App\Livewire\ReservationForm::class)
            ->set('apartment_id', $apt->id)
            ->call('updateApartmentDetails')
            ->set('apartment_package_id', $pkg->id)
            ->call('selectDate', '2026-07-10')
            ->call('selectDate', '2026-07-12')
            ->set('first_name', 'Jane')
            ->set('last_name', 'Doe')
            ->set('email', $email)
            ->set('phone', '+420123456789')
            ->set('address', 'Street 1')
            ->set('city', 'Prague')
            ->set('postal_code', '11000')
            ->set('country', 'Czechia')
            ->call('confirm')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('reservations', 1);

        $reservation = Reservation::first();

        $this->assertEquals($apt->id, $reservation->apartment_id);
        $this->assertEquals($pkg->id, $reservation->apartment_package_id);
        $this->assertEquals($email, $reservation->user->email);

        Mail::assertQueued(ReservationConfirmation::class, function ($mail) use ($reservation) {
            return isset($mail->reservation) && $mail->reservation->id === $reservation->id;
        });

        $this->assertTrue(session()->get('reservation_completed', false));
    }
}
