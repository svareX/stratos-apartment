<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaticPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_terms_and_about_pages_render_with_auth(): void
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
            ->get('/en/contact')
            ->assertStatus(200);

        $this->withSession(['website_authenticated' => true])
            ->get('/en/terms-and-conditions')
            ->assertStatus(200);

        $this->withSession(['website_authenticated' => true])
            ->get('/en/about')
            ->assertStatus(200);

        $this->withSession(['website_authenticated' => true])
            ->get('/en/packages')
            ->assertStatus(200);

        $this->withSession(['website_authenticated' => true])
            ->get('/en/activities')
            ->assertStatus(200);

        $this->withSession(['website_authenticated' => true])
            ->get('/en/pricing')
            ->assertStatus(200);
    }
}
