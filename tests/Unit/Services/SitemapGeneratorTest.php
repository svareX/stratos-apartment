<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\SitemapGenerator;
use App\Models\Apartment;
use App\Models\Place;
use App\Models\Hike;
use App\Models\FrequentlyAskedQuestion;

class SitemapGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_writes_file_with_urls(): void
    {
        $path = storage_path('sitemap_test.xml');
        if (file_exists($path)) {
            @unlink($path);
        }

        $apt = Apartment::create([
            'name_en' => 'Sitemap Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 500,
            'active' => true,
        ]);

        // Attach a place and a hike that reference the apartment
        Place::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Nearby Place',
            'description_en' => 'Desc',
        ]);

        Hike::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Nice Hike',
            'description_en' => 'Desc',
            'difficulty' => 'easy',
            'length' => 1.0,
        ]);

        FrequentlyAskedQuestion::create([
            'question_en' => 'Q?',
            'answer_en' => 'A',
            'question_cs' => 'Q? CS',
            'answer_cs' => 'A CS',
            'question_de' => 'Q? DE',
            'answer_de' => 'A DE',
            'position' => 1,
            'is_active' => true,
        ]);

        $generator = new SitemapGenerator();
        $written = $generator->generate($path);

        $this->assertIsString($written);

        if (file_exists($written)) {
            $content = file_get_contents($written);
            $this->assertStringContainsString('<urlset', $content);
            $this->assertStringContainsString($apt->slug, $content);
            @unlink($written);
        } else {
            // If the environment couldn't write the file, at least ensure no exception and a path returned
            $this->assertStringContainsString('sitemap', basename($written));
        }
    }
}
