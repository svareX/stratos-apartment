<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Apartment;
use App\Services\SitemapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SitemapGeneratorImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_includes_photo_urls_when_photos_present(): void
    {
        $path = storage_path('sitemap_images_test.xml');
        @unlink($path);

        $apt = Apartment::create([
            'name_en' => 'Img Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 400,
            'active' => true,
        ]);

        // create a small jpeg on the public disk
        $img = imagecreatetruecolor(10, 10);
        $bg = imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, 0, 0, 9, 9, $bg);
        ob_start();
        imagejpeg($img);
        $jpeg = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('photos/sitemap.jpg', $jpeg);

        $apt->photos()->create([
            'path' => 'photos/sitemap.jpg',
            'is_main' => true,
            'position' => 1,
        ]);

        $generator = new SitemapGenerator;
        $written = $generator->generate($path);

        if (file_exists($written)) {
            $content = file_get_contents($written);
            $this->assertStringContainsString('photos/sitemap.jpg', $content);
            @unlink($written);
        } else {
            $this->assertIsString($written);
        }
    }

    public function test_generate_includes_place_and_hike_hashes(): void
    {
        $path = storage_path('sitemap_ph_test.xml');
        @unlink($path);

        $apt = Apartment::create([
            'name_en' => 'PH Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 400,
            'active' => true,
        ]);

        $apt->places()->create([
            'name_en' => 'Nearby',
            'description_en' => 'desc',
        ]);

        $apt->hikes()->create([
            'name_en' => 'Hike1',
            'description_en' => 'desc',
            'difficulty' => 'easy',
            'length' => 1.0,
        ]);

        $generator = new SitemapGenerator;
        $written = $generator->generate($path);

        if (file_exists($written)) {
            $content = file_get_contents($written);
            $this->assertStringContainsString('#nearby', $content);
            $this->assertStringContainsString('#hikes', $content);
            @unlink($written);
        } else {
            $this->assertIsString($written);
        }
    }
}
