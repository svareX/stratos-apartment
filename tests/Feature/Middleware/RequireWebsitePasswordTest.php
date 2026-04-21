<?php

declare(strict_types=1);

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;

class RequireWebsitePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_to_password_when_set_and_not_authenticated(): void
    {
        config(['app.website_password' => '1']); // use the config key your middleware actually checks

        Apartment::create([
            'name_en' => 'Nav Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $this->get('/en/')->assertRedirect('/password');
    }

    public function test_allows_password_route_when_set(): void
    {
        putenv('WEBSITE_PASSWORD=1');

        $res = $this->get('/password');

        $res->assertStatus(200);
    }

    public function test_allows_access_when_password_not_set(): void
    {
        // ensure WEBSITE_PASSWORD is cleared for this test
        putenv('WEBSITE_PASSWORD');
        unset($_ENV['WEBSITE_PASSWORD']);
        unset($_SERVER['WEBSITE_PASSWORD']);

        Apartment::create([
            'name_en' => 'Nav Apt 2',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $res = $this->get('/en/');

        $res->assertStatus(200);
    }
}
