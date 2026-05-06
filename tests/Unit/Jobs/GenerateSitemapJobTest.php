<?php

namespace Tests\Unit\Jobs;

use App\Jobs\GenerateSitemap;
use App\Services\SitemapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GenerateSitemapJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_calls_generator()
    {
        $mock = Mockery::mock(SitemapGenerator::class);
        $mock->shouldReceive('generate')->once()->andReturn('/tmp/sitemap.xml');

        $this->instance(SitemapGenerator::class, $mock);

        $job = new GenerateSitemap;
        $job->handle();

        $this->assertTrue(true); // no exceptions
    }
}
