<?php

declare(strict_types=1);

namespace Tests\Unit\Mail;

use App\Mail\ReservationConfirmation;
use App\Models\Apartment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_envelope_and_content_are_configured(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Mail Apt',
            'slug' => 'mail-apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $user = User::factory()->create();

        $reservation = Reservation::create([
            'apartment_id' => $apt->id,
            'user_id' => $user->id,
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'price' => 500.00,
        ]);

        $mailable = new ReservationConfirmation($reservation);

        $envelope = $mailable->envelope();
        $this->assertStringContainsString(__('Reservation Confirmation'), $envelope->subject);

        $content = $mailable->content();
        $this->assertEquals('emails.reservation.confirmation', $content->markdown);
    }
}
