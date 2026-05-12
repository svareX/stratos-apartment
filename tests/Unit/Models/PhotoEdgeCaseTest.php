<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotoEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_returns_empty_string_when_missing_and_locale_behaviour()
    {
        $apt = Apartment::create([
            'name_en' => 'Photo Apt',
            'address' => 'Addr',
            'capacity' => 1,
            'base_price' => 10,
        ]);

        $p = Photo::create([
            'apartment_id' => $apt->id,
            'path' => 'images/1.jpg',
        ]);

        app()->setLocale('en');
        $this->assertSame('', $p->tag);

        $p2 = Photo::create([
            'apartment_id' => $apt->id,
            'path' => 'images/2.jpg',
            'tag_en' => 'english',
            'tag_cs' => 'czech',
        ]);

        app()->setLocale('en');
        $this->assertEquals('english', $p2->tag);

        app()->setLocale('cs');
        $this->assertEquals('czech', $p2->tag);

        app()->setLocale('de');
        $this->assertSame('', $p2->tag);
    }
}
