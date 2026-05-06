<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentTranslationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_name_attribute_respects_locale()
    {
        $apt = Apartment::create([
            'name_en' => 'English Name',
            'name_cs' => 'Czech Name',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
        ]);

        app()->setLocale('cs');
        $this->assertEquals('Czech Name', $apt->name);

        app()->setLocale('en');
        $this->assertEquals('English Name', $apt->name);
    }
}
