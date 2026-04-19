<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class ImageProxyBranchesTest extends TestCase
{
    use RefreshDatabase;

    public function test_url_and_storage_prefixes(): void
    {
        $img = imagecreatetruecolor(20, 10);
        $bg = imagecolorallocate($img, 50, 100, 150);
        imagefilledrectangle($img, 0, 0, 19, 9, $bg);
        ob_start();
        imagejpeg($img);
        $jpeg = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('photos/urltest.jpg', $jpeg);

        // path as full URL containing /storage/
        $res1 = $this->get('/img?path=http://localhost/storage/photos/urltest.jpg&w=100');
        $res1->assertStatus(200);
        $res1->assertHeader('Content-Type', 'image/jpeg');

        // path starting with /storage/
        $res2 = $this->get('/img?path=/storage/photos/urltest.jpg&w=100');
        $res2->assertStatus(200);
        $res2->assertHeader('Content-Type', 'image/jpeg');

        // path starting with storage/
        $res3 = $this->get('/img?path=storage/photos/urltest.jpg&w=100');
        $res3->assertStatus(200);
        $res3->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_uppercase_extension_creates_cache(): void
    {
        $img = imagecreatetruecolor(20, 10);
        $bg = imagecolorallocate($img, 200, 60, 40);
        imagefilledrectangle($img, 0, 0, 19, 9, $bg);
        ob_start();
        imagejpeg($img);
        $jpeg = ob_get_clean();
        imagedestroy($img);

        $path = 'photos/UPPER.JPG';
        Storage::disk('public')->put($path, $jpeg);

        $width = 80;
        $res = $this->get('/img?path=' . urlencode($path) . '&w=' . $width);
        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'image/jpeg');

        $cacheFile = storage_path('app/public/_cache/' . md5($path) . "-{$width}.jpg");
        $this->assertFileExists($cacheFile);
    }

    public function test_gif_branch_serves_gif(): void
    {
        if (! function_exists('imagegif')) {
            $this->markTestSkipped('GIF support not available in GD');
        }

        $img = imagecreatetruecolor(10, 10);
        $bg = imagecolorallocate($img, 0, 255, 0);
        imagefilledrectangle($img, 0, 0, 9, 9, $bg);
        ob_start();
        imagegif($img);
        $gif = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('photos/test.gif', $gif);

        $res = $this->get('/img?path=photos/test.gif&w=50');
        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'image/gif');
    }

    public function test_webp_branch_serves_webp_or_skips(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('WebP support not available in GD');
        }

        $img = imagecreatetruecolor(10, 10);
        $bg = imagecolorallocate($img, 100, 100, 255);
        imagefilledrectangle($img, 0, 0, 9, 9, $bg);
        ob_start();
        imagewebp($img);
        $webp = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('photos/test.webp', $webp);

        $res = $this->get('/img?path=photos/test.webp&w=40');
        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'image/webp');
    }
}
