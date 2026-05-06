<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_attribute_returns_locale_specific_value()
    {
        $apt = Apartment::create([
            'name_en' => 'Photo Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
        ]);

        $photo = $apt->photos()->create([
            'path' => 'photos/x.jpg',
            'tag_en' => 'en tag',
            'tag_cs' => 'cs tag',
            'is_main' => true,
            'position' => 1,
        ]);

        app()->setLocale('en');
        $this->assertEquals('en tag', $photo->tag);

        app()->setLocale('cs');
        $this->assertEquals('cs tag', $photo->tag);

        // non-available locale should return empty string
        app()->setLocale('de');
        $this->assertEquals('', $photo->tag);
    }

    public function test_is_main_and_path_are_accessible()
    {
        Storage::fake('public');

        $apt = Apartment::create([
            'name_en' => 'Photo Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
        ]);

        Storage::disk('public')->put('photos/test.jpg', 'bytes');

        $photo = $apt->photos()->create([
            'path' => 'photos/test.jpg',
            'is_main' => true,
            'position' => 1,
        ]);

        $this->assertTrue($photo->is_main);
        $this->assertStringContainsString('photos/test.jpg', $photo->path);
    }
}
