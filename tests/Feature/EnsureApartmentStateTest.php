<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureApartmentStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_to_unavailable_when_apartment_inactive(): void
    {
        // ensure website password is cleared for these requests
        putenv('WEBSITE_PASSWORD');
        unset($_ENV['WEBSITE_PASSWORD']);
        unset($_SERVER['WEBSITE_PASSWORD']);
        config(['app.website_password' => '']);

        $apt = Apartment::create([
            'name_en' => 'Unavailable Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => false,
            'slug' => 'unavailable-apt',
        ]);

        $res = $this->get('/en/apartments/unavailable-apt');

        // Should redirect to the unavailable route (which also includes locale)
        $res->assertRedirect(route('apartments.unavailable', ['locale' => 'en', 'apartment' => $apt->slug]));
    }

    public function test_shows_apartment_when_active(): void
    {
        // ensure website password is cleared for these requests
        putenv('WEBSITE_PASSWORD');
        unset($_ENV['WEBSITE_PASSWORD']);
        unset($_SERVER['WEBSITE_PASSWORD']);
        config(['app.website_password' => '']);

        $apt = Apartment::create([
            'name_en' => 'Active Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
            'slug' => 'active-apt',
        ]);

        $res = $this->get('/en/apartments/active-apt');

        $res->assertStatus(200);
    }

    public function test_inactive_route_allows_inactive_apartment(): void
    {
        // ensure website password is cleared for these requests
        putenv('WEBSITE_PASSWORD');
        unset($_ENV['WEBSITE_PASSWORD']);
        unset($_SERVER['WEBSITE_PASSWORD']);
        config(['app.website_password' => '']);

        $apt = Apartment::create([
            'name_en' => 'Inactive Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => false,
            'slug' => 'inactive-apt',
        ]);

        $res = $this->get(route('apartments.unavailable', ['locale' => 'en', 'apartment' => $apt->slug]));

        $res->assertStatus(200);
    }
}
