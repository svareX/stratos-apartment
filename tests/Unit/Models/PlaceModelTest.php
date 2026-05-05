<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PlaceModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_dynamic_seo_data_uses_image_when_present()
    {
        Storage::fake('public');

        $apt = Apartment::create([
            'name_en' => 'Place Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 300,
        ]);

        // create an image
        $img = imagecreatetruecolor(5, 5);
        ob_start();
        imagejpeg($img);
        $jpeg = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('places/p.jpg', $jpeg);

        $place = Place::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Near',
            'description_en' => 'Desc',
            'image' => 'places/p.jpg',
        ]);

        $seo = $place->getDynamicSEOData();
        $this->assertStringContainsString('places/p.jpg', (string) $seo->image);
    }

    public function test_get_dynamic_seo_data_returns_route_when_no_image()
    {
        $apt = Apartment::create([
            'name_en' => 'Place Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 300,
        ]);

        $place = Place::create([
            'apartment_id' => $apt->id,
            'name_en' => 'NoImg',
            'description_en' => 'Desc',
        ]);

        $seo = $place->getDynamicSEOData();
        $this->assertIsString($seo->image);
    }
}
