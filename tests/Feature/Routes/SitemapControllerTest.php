<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\SitemapGenerator;

class SitemapControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_serves_generated_file_from_bound_generator(): void
    {
        @unlink(public_path('sitemap.xml'));

        $path = public_path('sitemap_test_generated.xml');

        $fake = new class {
            public function generate($path)
            {
                file_put_contents($path, '<?xml version="1.0"?><urlset><url><loc>FAKE</loc></url></urlset>');
                return $path;
            }
        };

        $this->app->instance(SitemapGenerator::class, $fake);

        $res = $this->get('/sitemap.xml');

        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'application/xml');

        $this->assertFileExists(public_path('sitemap.xml'));
        $this->assertStringContainsString('FAKE', file_get_contents(public_path('sitemap.xml')));

        @unlink(public_path('sitemap.xml'));
    }

    public function test_uses_spatie_fallback_when_generator_throws(): void
    {
        @unlink(public_path('sitemap.xml'));

        $thrower = new class {
            public function generate($path)
            {
                throw new \Exception('boom');
            }
        };

        $this->app->instance(SitemapGenerator::class, $thrower);

        $res = $this->get('/sitemap.xml');

        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'application/xml');

        $this->assertFileExists(public_path('sitemap.xml'));
        $this->assertStringContainsString('<urlset', file_get_contents(public_path('sitemap.xml')));

        @unlink(public_path('sitemap.xml'));
    }

    public function test_blade_fallback_when_spatie_unwritable_and_generator_fails(): void
    {
        @unlink(public_path('sitemap.xml'));

        $thrower = new class {
            public function generate($path)
            {
                throw new \Exception('boom');
            }
        };

        $this->app->instance(\App\Services\SitemapGenerator::class, $thrower);

        $publicPath = public_path();
        $oldPerms = fileperms($publicPath) & 0777;
        chmod($publicPath, 0555);

        try {
            $res = $this->get('/sitemap.xml');

            $res->assertStatus(200);
            $res->assertHeader('Content-Type', 'application/xml');
            $res->assertSee('<urlset', false);
        } finally {
            chmod($publicPath, $oldPerms);
            @unlink(public_path('sitemap.xml'));
        }
    }

    public function test_serves_existing_sitemap_file(): void
    {
        @unlink(public_path('sitemap.xml'));

        file_put_contents(public_path('sitemap.xml'), '<?xml version="1.0"?><urlset><url><loc>EXIST</loc></url></urlset>');

        $res = $this->get('/sitemap.xml');

        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'application/xml');

        $this->assertStringContainsString('EXIST', file_get_contents(public_path('sitemap.xml')));

        @unlink(public_path('sitemap.xml'));
    }
}
