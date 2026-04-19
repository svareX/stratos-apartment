<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class RobotsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        @unlink(public_path('robots.txt'));
        Cache::forget('robots-txt');

        parent::tearDown();
    }

    public function test_returns_default_rules_when_no_file(): void
    {
        @unlink(public_path('robots.txt'));
        Cache::forget('robots-txt');

        $res = $this->get(route('robots'));

        $res->assertStatus(200);
        $this->assertStringContainsString('text/plain', $res->baseResponse->headers->get('Content-Type'));
        $res->assertSee('User-agent: *', false);
        $res->assertSee('Sitemap: ' . rtrim(config('services.sitemap.base_url', config('app.url')), '/') . '/sitemap.xml', false);
    }

    public function test_reads_existing_robots_and_inserts_sitemap_if_missing(): void
    {
        $content = "User-agent: *\nDisallow: /admin\n";
        file_put_contents(public_path('robots.txt'), $content);
        Cache::forget('robots-txt');

        $res = $this->get(route('robots'));

        $res->assertStatus(200);
        $this->assertStringContainsString('text/plain', $res->baseResponse->headers->get('Content-Type'));
        $res->assertSee('Disallow: /admin', false);
        $res->assertSee('Sitemap: ' . rtrim(config('services.sitemap.base_url', config('app.url')), '/') . '/sitemap.xml', false);
    }

    public function test_updates_sitemap_line_when_present_and_different(): void
    {
        $content = "Sitemap: http://example.com/sitemap.xml\n";
        file_put_contents(public_path('robots.txt'), $content);
        Cache::forget('robots-txt');

        $res = $this->get(route('robots'));

        $res->assertStatus(200);
        $this->assertStringContainsString('text/plain', $res->baseResponse->headers->get('Content-Type'));
        $res->assertSee('Sitemap: ' . rtrim(config('services.sitemap.base_url', config('app.url')), '/') . '/sitemap.xml', false);
    }
}
