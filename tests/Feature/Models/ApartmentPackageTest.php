<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Apartment;
use App\Models\ApartmentPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentPackageTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_apartment_package(): void
    {
        $apartment = Apartment::create([
            'name_en' => 'Package Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $package = ApartmentPackage::create([
            'apartment_id' => $apartment->id,
            'name_en' => 'Basic',
            'name_cs' => 'Základní',
            'features' => [
                ['en' => 'WiFi', 'cs' => 'Wi-Fi'],
                ['en' => 'Breakfast'],
            ],
            'price' => 99.99,
            'icon' => 'icon-basic',
        ]);

        $this->assertDatabaseHas('apartment_packages', ['apartment_id' => $apartment->id, 'name_en' => 'Basic']);
        $this->assertEquals(99.99, (float) $package->price);

        $package->update(['price' => 149.5, 'name_en' => 'Standard']);

        $this->assertEquals(149.5, (float) $package->fresh()->price);
        $this->assertDatabaseHas('apartment_packages', ['id' => $package->id, 'name_en' => 'Standard']);

        $package->delete();
        $this->assertDatabaseMissing('apartment_packages', ['id' => $package->id]);
    }
}
