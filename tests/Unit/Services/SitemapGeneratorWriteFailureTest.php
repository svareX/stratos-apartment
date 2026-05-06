<?php

namespace Tests\Unit\Services;

use App\Services\SitemapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapGeneratorWriteFailureTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_returns_path_even_when_unwritable()
    {
        // choose a path that's likely unwritable (root)
        $path = '/root/sitemap_nowrite.xml';

        $generator = new SitemapGenerator;
        $written = $generator->generate($path);

        $this->assertIsString($written);
        $this->assertStringContainsString('sitemap', basename($written));
    }
}
