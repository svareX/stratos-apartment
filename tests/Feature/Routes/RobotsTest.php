<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RobotsTest extends TestCase
{
    use RefreshDatabase;

    public function test_robots_returns_text_plain_with_sitemap_line(): void
    {
        @unlink(public_path('robots.txt'));

        $res = $this->get('/robots.txt');

        $res->assertStatus(200);
        $this->assertStringContainsString('text/plain', $res->headers->get('Content-Type'));
        $res->assertSee('Sitemap:');
    }
}
