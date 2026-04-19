<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class ImageProxyTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_proxy_serves_resized_png(): void
    {
        // generate a small PNG via GD to ensure valid image
        $img = imagecreatetruecolor(10, 10);
        $bg = imagecolorallocate($img, 255, 0, 0);
        imagefilledrectangle($img, 0, 0, 9, 9, $bg);
        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('photos/test.png', $png);

        $res = $this->get('/img?path=photos/test.png&w=100');

        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'image/png');
    }

    public function test_image_proxy_returns_404_for_missing_file(): void
    {
        $res = $this->get('/img?path=photos/missing.png&w=100');
        $res->assertStatus(404);
    }

    public function test_image_proxy_serves_original_for_unsupported_extension(): void
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg"><rect width="10" height="10" fill="#000"/></svg>';
        Storage::disk('public')->put('photos/test.svg', $svg);

        $res = $this->get('/img?path=photos/test.svg&w=100');

        $res->assertStatus(200);
    }
}
