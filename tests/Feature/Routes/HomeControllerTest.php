<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;
use Illuminate\Support\Facades\Storage;
use App\Models\HomepageSettings;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_with_default_hero_slides(): void
    {
        // Ensure no homepage settings present
        \DB::table('homepage_settings')->delete();

        // ensure at least one apartment exists so navigation can generate apartment routes
        \App\Models\Apartment::create([
            'name_en' => 'Nav Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $res = $this->withSession(['website_authenticated' => true])->get(route('home', ['locale' => 'en']));

        $res->assertStatus(200);
        $res->assertViewIs('home');
        $res->assertViewHas('heroSlides', function ($slides) {
            return is_array($slides) && count($slides) === 2;
        });
    }

    public function test_home_includes_gallery_images_from_apartments(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Gallery Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $apt->photos()->create([
            'path' => 'photos/g1.jpg',
            'is_main' => false,
            'position' => 1,
        ]);

        $res = $this->withSession(['website_authenticated' => true])->get(route('home', ['locale' => 'en']));

        $res->assertStatus(200);
        $res->assertViewIs('home');
        $res->assertViewHas('galleryImages', function ($images) {
            return $images->count() >= 1;
        });
    }

    public function test_hero_slides_use_storage_url_when_image_present(): void
    {
        \DB::table('homepage_settings')->delete();

        Storage::fake('public');

        $img = imagecreatetruecolor(20, 10);
        $bg = imagecolorallocate($img, 10, 20, 30);
        imagefilledrectangle($img, 0, 0, 19, 9, $bg);
        ob_start();
        imagejpeg($img);
        $jpeg = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('hero/hs.jpg', $jpeg);

        HomepageSettings::create([
            'hero_slides' => [
                [
                    'image' => 'hero/hs.jpg',
                    'title_en' => 'Hello',
                    'highlighted_title_en' => 'World',
                    'description_en' => 'Desc',
                ],
            ],
        ]);

        // ensure at least one apartment exists so navigation can generate apartment routes
        Apartment::create([
            'name_en' => 'Nav Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $res = $this->withSession(['website_authenticated' => true])->get(route('home', ['locale' => 'en']));

        $res->assertStatus(200);
        $res->assertViewHas('heroSlides', function ($slides) {
            return isset($slides[0]['image_url']) && str_contains($slides[0]['image_url'], 'hero/hs.jpg');
        });
    }
}
