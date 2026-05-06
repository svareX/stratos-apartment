<?php

namespace Tests\Unit\Services;

use App\Models\Apartment;
use App\Services\SitemapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SitemapGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_writes_file()
    {
        Storage::fake('public');

        $apt = Apartment::create([
            'name_en' => 'Sitemap Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'slug' => 'sitemap-apt',
            'active' => true,
        ]);

        $path = storage_path('app/sitemap-test.xml');
        if (file_exists($path)) {
            @unlink($path);
        }

        $gen = new SitemapGenerator;
        $written = $gen->generate($path);

        $this->assertFileExists($written);
        $contents = file_get_contents($written);
        $this->assertStringContainsString('<urlset', $contents);
    }
}
