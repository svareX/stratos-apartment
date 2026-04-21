<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_and_locale_pages_load(): void
    {
        $this->get('/')->assertRedirect();

        $session = ['website_authenticated' => true];

        // Ensure at least one apartment exists because navigation expects one to build links
        $apartment = Apartment::create([
            'name_en' => 'Route Apt',
            'slug' => 'route-apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $this->withSession($session)->get('/en')->assertStatus(200);

        $this->withSession($session)->get('/en/apartments/route-apt')->assertStatus(200);

        $this->withSession($session)->get('/en/contact')->assertStatus(200);
        $this->withSession($session)->get('/en/reservation')->assertStatus(200);
        $this->withSession($session)->get('/en/terms-and-conditions')->assertStatus(200);
        $this->withSession($session)->get('/en/cookies')->assertStatus(200);

        $this->get('/sitemap.xml')->assertStatus(200);
        $this->get('/robots.txt')->assertStatus(200);
    }

    public function test_social_preview_returns_svg(): void
    {
        $apartment = Apartment::create([
            'name_en' => 'OG Apt',
            'slug' => 'og-apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $resp = $this->get('/og-image/apartment/og-apt.svg');
        $resp->assertStatus(200);
        $this->assertStringContainsString('<svg', $resp->getContent());
        $this->assertEquals('image/svg+xml', $resp->headers->get('Content-Type'));
    }
}
