<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\RelationManagers;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackagesRelationManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_update_and_delete_package(): void
    {
        $apt = Apartment::create([
            'name_en' => 'RelMgr Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1200,
            'active' => true,
        ]);

        $package = $apt->packages()->create([
            'name_en' => 'Basic',
            'price' => 100.0,
            'icon' => '⭐',
            'features' => [['en' => 'Wifi'], ['en' => 'Breakfast']],
        ]);

        $this->assertDatabaseHas('apartment_packages', ['id' => $package->id, 'name_en' => 'Basic']);

        $package->update(['price' => 200.0]);

        $this->assertDatabaseHas('apartment_packages', ['id' => $package->id, 'price' => 200.0]);

        $package->delete();

        $this->assertDatabaseMissing('apartment_packages', ['id' => $package->id]);
    }
}
