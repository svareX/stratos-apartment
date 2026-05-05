<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_upsert_updates_existing_reservation_by_external_and_apartment()
    {
        $apt = Apartment::create([
            'name_en' => 'Res Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
        ]);

        $res = Reservation::create([
            'apartment_id' => $apt->id,
            'check_in' => now()->toDateString(),
            'check_out' => now()->addDay()->toDateString(),
            'price' => 100,
            'external_booking_id' => 'ext-1',
        ]);

        // upsert with same external_booking_id + apartment_id should update price
        Reservation::upsert([
            [
                'apartment_id' => $apt->id,
                'external_booking_id' => 'ext-1',
                'check_in' => now()->toDateString(),
                'check_out' => now()->addDay()->toDateString(),
                'price' => 200,
            ],
        ], ['external_booking_id','apartment_id'], ['price']);

        $res->refresh();
        $this->assertEquals(200.00, round($res->price, 2));
    }
}
