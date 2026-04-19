<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_pages_return_ok(): void
    {
        // Ensure at least one apartment exists because navigation expects one to build links
        $apartment = new Apartment();
        $apartment->name_en = 'Test Apt';
        $apartment->address = 'Addr';
        $apartment->capacity = 2;
        $apartment->base_price = 1000;
        $apartment->slug = 'test-apt';
        $apartment->active = true;
        $apartment->save();

        $response = $this->withSession(['website_authenticated' => true])->get(route('reservation', ['locale' => 'en']));
        $response->assertStatus(200);

        $response = $this->withSession(['website_authenticated' => true])->get(route('reservation.result', ['locale' => 'en']));
        $response->assertStatus(200);
    }

    public function test_apartment_show_returns_ok_for_existing_apartment(): void
    {
        $apartment = new Apartment();
        $apartment->name_en = 'Test Apt';
        $apartment->address = 'Addr';
        $apartment->capacity = 2;
        $apartment->base_price = 1000;
        $apartment->slug = 'test-apt';
        $apartment->active = true;
        $apartment->save();

        $response = $this->withSession(['website_authenticated' => true])->get(route('apartments.show', ['locale' => 'en', 'apartment' => $apartment->slug]));

        $response->assertStatus(200);
    }
}
