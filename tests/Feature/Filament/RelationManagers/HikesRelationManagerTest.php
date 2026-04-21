<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\RelationManagers;

use App\Enums\HikeDifficulty;
use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HikesRelationManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_update_and_delete_hike(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Hike Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1200,
            'active' => true,
        ]);

        $hike = $apt->hikes()->create([
            'name_en' => 'Short Walk',
            'length' => 2.5,
            'difficulty' => HikeDifficulty::Easy->value,
            'is_for_families' => true,
            'distance_tx_en' => '↑ 100 m',
        ]);

        $this->assertDatabaseHas('hikes', ['id' => $hike->id, 'name_en' => 'Short Walk']);

        $hike->update(['length' => 3.0]);

        $this->assertDatabaseHas('hikes', ['id' => $hike->id, 'length' => 3.0]);

        $hike->delete();

        $this->assertDatabaseMissing('hikes', ['id' => $hike->id]);
    }
}
