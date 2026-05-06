<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservations_relation_and_user_deletion_sets_null()
    {
        $user = User::create([
            'name' => 'Res User',
            'email' => 'res@example.com',
            'password' => bcrypt('secret'),
        ]);

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
            'price' => 50,
        ]);

        $this->assertCount(1, $user->reservations);

        $user->delete();

        $res->refresh();
        $this->assertNull($res->user_id);
    }
}
