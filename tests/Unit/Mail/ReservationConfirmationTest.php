<?php

declare(strict_types=1);

namespace Tests\Unit\Mail;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Mail\ReservationConfirmation;
use App\Models\Apartment;
use App\Models\Reservation;

class ReservationConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_envelope_and_content_are_configured(): void
    {
        $apartment = new Apartment();
        $apartment->name_en = 'Test Apt';
        $apartment->address = 'Addr';
        $apartment->capacity = 2;
        $apartment->base_price = 1000;
        $apartment->active = true;
        $apartment->save();

        $reservation = Reservation::create([
            'apartment_id' => $apartment->id,
            'check_in' => now()->toDateString(),
            'check_out' => now()->addDays(1)->toDateString(),
            'price' => 1000,
            'status' => 'pending',
            'booking_source' => 'local',
        ]);

        $mailable = new ReservationConfirmation($reservation);

        $this->assertStringContainsString('Reservation Confirmation', $mailable->envelope()->subject);
        $this->assertEquals('emails.reservation.confirmation', $mailable->content()->markdown);
    }
}
