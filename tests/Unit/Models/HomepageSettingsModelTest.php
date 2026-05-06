<?php

namespace Tests\Unit\Models;

use App\Models\HomepageSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageSettingsModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_slides_casts_to_array()
    {
        $h = HomepageSettings::create([
            'hero_slides' => [
                ['title' => 'Slide1'],
            ],
        ]);

        $this->assertIsArray($h->hero_slides);
        $this->assertEquals('Slide1', $h->hero_slides[0]['title']);
    }
}
