<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\GenerateSitemap;
use App\Services\SitemapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenerateSitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_invokes_generator_and_writes_file(): void
    {
        @unlink(public_path('sitemap.xml'));

        $fake = new class
        {
            public function generate($path = null)
            {
                $path = $path ?? public_path('sitemap.xml');
                file_put_contents($path, '<?xml version="1.0"?><urlset><url><loc>JOBFAKE</loc></url></urlset>');

                return $path;
            }
        };

        $this->app->instance(SitemapGenerator::class, $fake);

        $job = new GenerateSitemap;
        $job->handle();

        $this->assertFileExists(public_path('sitemap.xml'));
        $this->assertStringContainsString('JOBFAKE', file_get_contents(public_path('sitemap.xml')));

        @unlink(public_path('sitemap.xml'));
    }
}
