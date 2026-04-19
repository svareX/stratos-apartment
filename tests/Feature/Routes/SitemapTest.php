<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_xml_when_no_file(): void
    {
        // ensure no sitemap file exists
        @unlink(public_path('sitemap.xml'));

        $res = $this->get('/sitemap.xml');

        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'application/xml');
    }

    public function test_sitemap_serves_existing_file(): void
    {
        $content = '<?xml version="1.0"?><urlset></urlset>';
        file_put_contents(public_path('sitemap.xml'), $content);

        $res = $this->get('/sitemap.xml');

        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'application/xml');
    }
}
