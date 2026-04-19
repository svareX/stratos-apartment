<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;
use App\Models\User;
use App\Models\Reservation;

class ReservationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_reservation(): void
    {
        $apartment = Apartment::create([
            'name_en' => 'Res Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $user = User::create([
            'name' => 'Res User',
            'email' => 'resuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $reservation = Reservation::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user->id,
            'check_in' => now()->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
            'price' => 2000,
            'status' => 'pending',
            'booking_source' => 'local',
        ]);

        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'apartment_id' => $apartment->id]);

        $reservation->update(['status' => 'confirmed', 'price' => 2500]);
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'status' => 'confirmed', 'price' => 2500]);

        $reservation->delete();
        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }
}
