<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_reservations_relation()
    {
        $user = User::factory()->create();

        $apt = Apartment::create([
            'name_en' => 'User Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
        ]);

        $res = Reservation::create([
            'apartment_id' => $apt->id,
            'user_id' => $user->id,
            'check_in' => now()->toDateString(),
            'check_out' => now()->addDay()->toDateString(),
            'price' => 10,
            'status' => 'pending',
            'booking_source' => 'local',
        ]);

        $this->assertEquals(1, $user->reservations()->count());
    }
}
