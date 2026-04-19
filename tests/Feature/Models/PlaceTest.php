<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;
use App\Models\Place;

class PlaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_place(): void
    {
        $apartment = Apartment::create([
            'name_en' => 'Place Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $place = Place::create([
            'apartment_id' => $apartment->id,
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

        $this->assertDatabaseHas('places', ['apartment_id' => $apartment->id, 'name_en' => 'Museum']);

        $place->update(['name_en' => 'Updated Museum', 'distance_text_en' => '1km']);

        $this->assertDatabaseHas('places', ['id' => $place->id, 'name_en' => 'Updated Museum']);

        $place->delete();
        $this->assertDatabaseMissing('places', ['id' => $place->id]);
    }
}
