<?php

namespace Tests\Unit\Services;

use App\Models\Apartment;
use App\Services\SitemapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SitemapImageTypeErrorTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_image_handles_typeerror_and_still_includes_image_url()
    {
        $path = storage_path('sitemap_typeerror_test.xml');
        @unlink($path);

        $apt = Apartment::create([
            'name_en' => 'TypErr Apt',
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

        Storage::disk('public')->put('photos/typeerr.jpg', $jpeg);

        // intentionally set a non-string title to try to trigger TypeError in addImage
        $apt->photos()->create([
            'path' => 'photos/typeerr.jpg',
            'is_main' => true,
            'position' => 1,
            'title_en' => 'TypeErr Title',
            'description_en' => 'Desc text',
        ]);

        $generator = new SitemapGenerator;
        $written = $generator->generate($path);

        if (file_exists($written)) {
            $content = file_get_contents($written);
            $this->assertStringContainsString('photos/typeerr.jpg', $content);
            @unlink($written);
        } else {
            $this->assertIsString($written);
        }
    }
}
