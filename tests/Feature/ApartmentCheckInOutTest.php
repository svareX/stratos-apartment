<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentCheckInOutTest extends TestCase
{
    use RefreshDatabase;

    public function test_apartment_has_default_check_in_and_check_out_times(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Times Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $this->assertSame('15:00:00', $apt->check_in_time);
        $this->assertSame('10:00:00', $apt->check_out_time);

        $this->assertSame('15:00', $apt->check_in_time_formatted);
        $this->assertSame('10:00', $apt->check_out_time_formatted);
    }
}
