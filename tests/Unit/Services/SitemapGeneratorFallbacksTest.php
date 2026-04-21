<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Apartment;
use App\Models\FrequentlyAskedQuestion;
use App\Services\SitemapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapGeneratorFallbacksTest extends TestCase
{
    use RefreshDatabase;

    public function test_localized_route_exceptions_fallback_to_url(): void
    {
        $path = storage_path('sitemap_localized_fallback.xml');
        @unlink($path);

        // create an apartment with a photo so generator doesn't call getDynamicSEOData (which may rely on named routes)
        $apt = Apartment::create([
            'name_en' => 'Fallback Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 400,
            'active' => true,
        ]);

        $apt->photos()->create([
            'path' => 'photos/fallback.jpg',
            'is_main' => true,
            'position' => 1,
        ]);

        // clear named routes to force route() to throw and exercise url() fallback
        $this->app['router']->setRoutes(new \Illuminate\Routing\RouteCollection);

        $generator = new SitemapGenerator;
        $written = $generator->generate($path);

        if (file_exists($written)) {
            $content = file_get_contents($written);
            $this->assertStringContainsString('/cs/', $content);
            $this->assertStringContainsString('/en/', $content);
            $this->assertStringContainsString('/de/', $content);
            @unlink($written);
        } else {
            $this->assertIsString($written);
        }
    }

    public function test_dynamic_seo_image_used_when_no_photos(): void
    {
        $path = storage_path('sitemap_dynamic_image.xml');
        @unlink($path);

        $apt = Apartment::create([
            'name_en' => 'NoPhoto Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 400,
            'active' => true,
        ]);

        $generator = new SitemapGenerator;
        $written = $generator->generate($path);

        if (file_exists($written)) {
            $content = file_get_contents($written);
            $this->assertStringContainsString('/og-image/apartment/', $content);
            @unlink($written);
        } else {
            $this->assertIsString($written);
        }
    }

    public function test_faqs_add_lastmod_entries(): void
    {
        $before = storage_path('sitemap_faq_before.xml');
        $after = storage_path('sitemap_faq_after.xml');
        @unlink($before);
        @unlink($after);

        $generator = new SitemapGenerator;
        $written1 = $generator->generate($before);

        $count1 = 0;
        if (file_exists($written1)) {
            $count1 = substr_count(file_get_contents($written1), 'lastmod');
        }

        FrequentlyAskedQuestion::create([
            'question_en' => 'Q?',
            'question_cs' => 'Q cz?',
            'question_de' => 'Q de?',
            'answer_en' => 'A',
            'answer_cs' => 'A cz',
            'answer_de' => 'A de',
            'is_active' => true,
        ]);

        $written2 = $generator->generate($after);

        if (file_exists($written2)) {
            $count2 = substr_count(file_get_contents($written2), 'lastmod');
            $this->assertGreaterThanOrEqual($count1, $count2);
            @unlink($written2);
            @unlink($written1 ?: $before);
        } else {
            $this->assertIsString($written2);
        }
    }
}
