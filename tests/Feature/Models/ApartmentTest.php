<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;

class ApartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_apartment(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Test Apartment',
            'address' => '123 Test St',
            'capacity' => 4,
            'base_price' => 1500,
            'active' => true,
        ]);

        $this->assertDatabaseHas('apartments', ['id' => $apt->id, 'name_en' => 'Test Apartment']);

        $apt->update(['name_en' => 'Updated Apartment', 'active' => false]);

        $this->assertDatabaseHas('apartments', ['id' => $apt->id, 'name_en' => 'Updated Apartment', 'active' => 0]);

        $apt->delete();
        $this->assertDatabaseMissing('apartments', ['id' => $apt->id]);
    }
}
