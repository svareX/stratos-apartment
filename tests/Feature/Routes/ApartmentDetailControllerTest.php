<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;

class ApartmentDetailControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_apartment_detail_with_slides_and_gallery(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Detail Apt',
            'slug' => 'detail-apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 200,
            'active' => true,
        ]);

        $apt->photos()->create([
            'path' => 'photos/main.jpg',
            'is_main' => true,
            'position' => 1,
        ]);

        $apt->photos()->create([
            'path' => 'photos/other.jpg',
            'is_main' => false,
            'position' => 2,
        ]);

        $res = $this->withSession(['website_authenticated' => true])->get(route('apartments.show', ['locale' => 'en', 'apartment' => $apt->slug]));

        $res->assertStatus(200);
        $res->assertViewIs('apartment.detail');
        $res->assertViewHas('slides', function ($slides) {
            return count($slides) === 1;
        });
        $res->assertViewHas('galleryPhotos', function ($photos) {
            return $photos->count() === 1;
        });
    }

    public function test_returns_404_for_missing_apartment(): void
    {
        $res = $this->withSession(['website_authenticated' => true])->get(route('apartments.show', ['locale' => 'en', 'apartment' => 'missing-apartment']));

        $res->assertStatus(404);
    }
}
