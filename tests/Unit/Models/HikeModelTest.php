<?php

namespace Tests\Unit\Models;

use App\Enums\HikeDifficulty;
use App\Models\Apartment;
use App\Models\Hike;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HikeModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_difficulty_casts_to_enum_and_dynamic_seo_url_when_no_apartment()
    {
        $apt = Apartment::create([
            'name_en' => 'Hike Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
        ]);

        $hike = Hike::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Mount',
            'description_en' => 'Nice',
            'difficulty' => 'easy',
            'length' => 2.5,
        ]);

        $this->assertInstanceOf(HikeDifficulty::class, $hike->difficulty);
        $this->assertEquals(HikeDifficulty::from('easy'), $hike->difficulty);

        $seo = $hike->getDynamicSEOData();
        $this->assertIsString($seo->url);
    }
}
