<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;

class SocialPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_og_image_returns_svg_for_apartment(): void
    {
        $apt = Apartment::create([
            'name_en' => 'OG Apt',
            'slug' => 'og-apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $res = $this->get(route('og.image', ['type' => 'apartment', 'identifier' => $apt->slug]));

        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'image/svg+xml');
        $res->assertSee('OG Apt');
    }

    public function test_og_image_404_for_unknown_model(): void
    {
        $res = $this->get(route('og.image', ['type' => 'hike', 'identifier' => '999999']));
        $res->assertStatus(404);
    }
}
