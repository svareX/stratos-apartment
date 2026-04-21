<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_page_requires_website_auth_and_renders(): void
    {
        // ensure navigation can build apartment links
        \App\Models\Apartment::create([
            'name_en' => 'Nav Apt',
            'slug' => 'nav-apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $this->withSession(['website_authenticated' => true])
            ->get('/en/reservation')
            ->assertStatus(200)
            ->assertViewIs('reservation');
    }

    public function test_reservation_result_page_requires_website_auth_and_renders(): void
    {
        \App\Models\Apartment::create([
            'name_en' => 'Nav Apt',
            'slug' => 'nav-apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $this->withSession(['website_authenticated' => true])
            ->get('/en/reservation/result')
            ->assertStatus(200)
            ->assertViewIs('reservation-result');
    }
}
