<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;
use App\Models\Photo;

class PhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_photo(): void
    {
        $apartment = Apartment::create([
            'name_en' => 'Photo Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $photo = Photo::create([
            'apartment_id' => $apartment->id,
            'path' => 'photos/1.jpg',
            'tag_en' => 'interior',
            'is_main' => true,
            'position' => 1,
        ]);

        $this->assertDatabaseHas('photos', ['apartment_id' => $apartment->id, 'path' => 'photos/1.jpg']);

        $photo->update(['path' => 'photos/2.jpg', 'is_main' => false]);

        $this->assertDatabaseHas('photos', ['id' => $photo->id, 'path' => 'photos/2.jpg', 'is_main' => 0]);

        $photo->delete();
        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);
    }
}
