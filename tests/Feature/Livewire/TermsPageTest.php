<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ContactSettings;

class TermsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_terms_page_route_renders_with_livewire_tag(): void
    {
        // Ensure contact settings exist for view
        ContactSettings::current();

        // Create an apartment so navigation links that call route('apartments.show') can be generated
        \App\Models\Apartment::create([
            'name_en' => 'Nav Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $response = $this->withSession(['website_authenticated' => true])
            ->get('/en/terms-and-conditions');

        $response->assertStatus(200)
            ->assertSee('terms-page');
    }
}
