<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;

class ApartmentTagsAndSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_tags_return_localized_values(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Tagged Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
            'tags_en' => [['value' => 'Pool'], ['value' => 'Wifi']],
            'tags_cs' => [['value' => 'Bazén']],
        ]);

        app()->setLocale('en');
        $this->assertEquals([['value' => 'Pool'], ['value' => 'Wifi']], $apt->tags);

        app()->setLocale('cs');
        $this->assertEquals([['value' => 'Bazén']], $apt->tags);
    }

    public function test_get_dynamic_seo_data_includes_slug_in_url(): void
    {
        $apt = Apartment::create([
            'name_en' => 'SEO Apt',
            'slug' => 'seo-apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        app()->setLocale('en');
        $seo = $apt->getDynamicSEOData();

        $this->assertStringContainsString($apt->slug, (string) $seo->url);
    }
}
