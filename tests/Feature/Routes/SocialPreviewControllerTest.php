<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Place;
use App\Models\Apartment;

class SocialPreviewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_type_returns_404(): void
    {
        $this->get('/og-image/unknown/1.svg')->assertStatus(404);
    }

    public function test_place_numeric_id_returns_svg(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Place Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $place = Place::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Museum',
            'name_cs' => 'Muzeum',
            'name_de' => 'Museum DE',
            'description_en' => 'Nice museum',
            'distance_text_en' => '500m',
            'icon' => 'map-pin',
            'image' => 'place.jpg',
            'latitude' => 50.089,
            'longitude' => 14.420,
            'url' => 'https://example.com',
        ]);

        $res = $this->get('/og-image/place/' . $place->id . '.svg');

        $res->assertStatus(200);
        $this->assertStringContainsString('<svg', $res->getContent());
        $this->assertEquals('image/svg+xml', $res->headers->get('Content-Type'));
    }

    public function test_missing_apartment_slug_returns_404(): void
    {
        $this->get('/og-image/apartment/non-existent.svg')->assertStatus(404);
    }
}
