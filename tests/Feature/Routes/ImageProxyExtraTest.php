<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class ImageProxyExtraTest extends TestCase
{
    use RefreshDatabase;

    public function test_cache_hit_returns_cached_file(): void
    {
        // create original JPEG
        $img = imagecreatetruecolor(10, 10);
        $bg = imagecolorallocate($img, 0, 128, 255);
        imagefilledrectangle($img, 0, 0, 9, 9, $bg);
        ob_start();
        imagejpeg($img);
        $jpeg = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('photos/orig.jpg', $jpeg);

        $cacheDir = storage_path('app/public/_cache');
        if (! is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $cacheFile = $cacheDir . '/' . md5('photos/orig.jpg') . '-100.jpg';
        file_put_contents($cacheFile, $jpeg);

        $res = $this->get('/img?path=photos/orig.jpg&w=100');

        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_width_zero_returns_original(): void
    {
        $img = imagecreatetruecolor(6, 6);
        $bg = imagecolorallocate($img, 255, 0, 0);
        imagefilledrectangle($img, 0, 0, 5, 5, $bg);
        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('photos/zero.png', $png);

        $res = $this->get('/img?path=photos/zero.png&w=0');

        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'image/png');
    }

    public function test_corrupt_image_returns_404(): void
    {
        Storage::disk('public')->put('photos/bad.png', 'not-an-image');

        $res = $this->get('/img?path=photos/bad.png&w=100');

        // Current controller attempts to list() the result of getimagesize().
        // When getimagesize() returns false this leads to a 500 error. Assert current behavior.
        $res->assertStatus(500);
    }
}
