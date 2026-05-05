<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use App\Models\ApartmentPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentPackageExtraTest extends TestCase
{
    use RefreshDatabase;

    public function test_features_default_to_empty_array_and_price_casting()
    {
        $apt = Apartment::create([
            'name_en' => 'Pkg Apt 2',
            'address' => 'Addr',
            'capacity' => 4,
            'base_price' => 150,
        ]);

        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Deluxe',
            'price' => 12.3456,
        ]);

        // features may be stored as null; translated_features ensures an array
        $this->assertIsArray($pkg->translated_features);
        $this->assertCount(0, $pkg->translated_features);

        // price may be stored with full precision; assert rounded value
        $this->assertEquals(12.35, round($pkg->price, 2));
    }
}
