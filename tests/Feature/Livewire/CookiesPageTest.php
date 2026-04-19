<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ContactSettings;

class CookiesPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_cookies_page_route_renders_with_livewire_tag(): void
    {
        ContactSettings::current();

        // Create an apartment for navigation links
        \App\Models\Apartment::create([
            'name_en' => 'Nav Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $response = $this->withSession(['website_authenticated' => true])
            ->get('/en/cookies');

        $response->assertStatus(200)
            ->assertSee('cookies-page');
    }
}
