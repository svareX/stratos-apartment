<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PlaceSEOFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_falls_back_to_route_and_uses_storage_when_present()
    {
        $apt = Apartment::create([
            'name_en' => 'Place Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
        ]);

        $place = Place::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Park',
            'description_en' => 'Nice park',
            'image' => null,
        ]);

        $seo = $place->getDynamicSEOData();
        $this->assertStringContainsString('og-image', $seo->image);

        // when image present, Storage::url should be used
        Storage::fake('public');
        $place->image = 'places/p1.jpg';
        $place->save();

        $seo2 = $place->getDynamicSEOData();
        $this->assertStringContainsString('places/p1.jpg', $seo2->image);
    }
}
