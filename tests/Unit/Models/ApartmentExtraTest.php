<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentExtraTest extends TestCase
{
    use RefreshDatabase;

    public function test_tags_behavior_and_slug_and_ical_token_generated()
    {
        $apt = Apartment::create([
            'name_en' => 'My Test Apartment',
            'address' => 'Somewhere',
            'capacity' => 3,
            'base_price' => 120,
            'tags_en' => ['wifi','balcony'],
            'tags_cs' => ['cs-tag'],
        ]);

        // slug auto-generated from name
        $this->assertStringContainsString('my-test-apartment', $apt->slug);

        // ical_export_token should be set by model boot
        $this->assertNotEmpty($apt->ical_export_token);

        // tags for cs locale
        app()->setLocale('cs');
        $this->assertEquals(['cs-tag'], $apt->tags);

        // tags for en locale
        app()->setLocale('en');
        $this->assertEquals(['wifi','balcony'], $apt->tags);
    }
}
