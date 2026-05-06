<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use App\Models\ApartmentPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentPackageModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_translated_features_return_expected_locale_values()
    {
        $apt = Apartment::create([
            'name_en' => 'Pkg Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 200,
        ]);

        $features = [
            ['en' => 'Wifi', 'cs' => 'Wifi CS'],
            ['en' => 'Breakfast', 'cs' => 'Snídaně'],
        ];

        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Standard',
            'features' => $features,
            'price' => 50,
        ]);

        app()->setLocale('cs');
        $translated = $pkg->translated_features;
        $this->assertContains('Wifi CS', $translated);
        $this->assertContains('Snídaně', $translated);

        app()->setLocale('en');
        $translatedEn = $pkg->translated_features;
        $this->assertContains('Wifi', $translatedEn);
    }
}
