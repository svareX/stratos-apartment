<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Apartment;
use App\Models\Hike;
use App\Enums\HikeDifficulty;

class HikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_hike(): void
    {
        $apartment = Apartment::create([
            'name_en' => 'Hike Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $hike = Hike::create([
            'apartment_id' => $apartment->id,
            'name_en' => 'Easy Trail',
            'difficulty' => HikeDifficulty::Easy->value,
            'length' => 3.5,
            'is_for_families' => true,
        ]);

        $this->assertDatabaseHas('hikes', ['apartment_id' => $apartment->id, 'name_en' => 'Easy Trail']);

        $hike->update(['name_en' => 'Updated Trail', 'difficulty' => HikeDifficulty::Hard->value]);

        $this->assertDatabaseHas('hikes', ['id' => $hike->id, 'name_en' => 'Updated Trail', 'difficulty' => HikeDifficulty::Hard->value]);

        $hike->delete();
        $this->assertDatabaseMissing('hikes', ['id' => $hike->id]);
    }
}
