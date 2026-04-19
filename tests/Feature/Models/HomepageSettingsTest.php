<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\HomepageSettings;

class HomepageSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_homepage_settings(): void
    {
        $settings = HomepageSettings::create([
            'hero_slides' => [
                ['title' => 'Slide 1', 'image' => 's1.jpg'],
            ],
        ]);

        $this->assertDatabaseHas('homepage_settings', ['id' => $settings->id]);

        $settings->update(['hero_slides' => [['title' => 'Slide X', 'image' => 'x.jpg']]]);

        $this->assertEquals('Slide X', $settings->fresh()->hero_slides[0]['title']);

        $settings->delete();
        $this->assertDatabaseMissing('homepage_settings', ['id' => $settings->id]);
    }

    public function test_hero_slides_storage_and_update(): void
    {
        $slides = [
            ['title' => 'Slide 1', 'image' => 's1.jpg'],
            ['title' => 'Slide 2', 'image' => 's2.jpg'],
        ];

        $settings = HomepageSettings::create(['hero_slides' => $slides]);

        $this->assertDatabaseHas('homepage_settings', ['id' => $settings->id]);

        $this->assertEquals($slides, $settings->hero_slides);

        $settings->update(['hero_slides' => [['title' => 'Updated', 'image' => 'u.jpg']]]);

        $this->assertEquals('Updated', $settings->fresh()->hero_slides[0]['title']);

        $settings->delete();
        $this->assertDatabaseMissing('homepage_settings', ['id' => $settings->id]);
    }
}
