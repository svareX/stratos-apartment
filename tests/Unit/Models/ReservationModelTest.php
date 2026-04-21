<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\ReservationStatus;
use App\Models\Apartment;
use App\Models\ApartmentPackage;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_casts_and_relationships(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Res Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 400,
            'active' => true,
        ]);

        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Pkg',
            'price' => 50,
        ]);

        $user = User::factory()->create();

        $res = Reservation::create([
            'apartment_id' => $apt->id,
            'user_id' => $user->id,
            'check_in' => '2026-06-01',
            'check_out' => '2026-06-05',
            'price' => 1000.00,
            'apartment_package_id' => $pkg->id,
            'package_price' => 50.00,
            'status' => ReservationStatus::Pending,
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $res->check_in);
        $this->assertSame('2026-06-01', $res->check_in->toDateString());

        $this->assertInstanceOf(ReservationStatus::class, $res->status);
        $this->assertTrue($res->status === ReservationStatus::Pending);

        $this->assertEquals($apt->id, $res->apartment->id);
        $this->assertEquals($user->id, $res->user->id);
        $this->assertEquals($pkg->id, $res->apartmentPackage->id);
    }
}
