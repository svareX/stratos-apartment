<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ContactSettings;

class ContactSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_creates_default_and_updates(): void
    {
        $settings = ContactSettings::current();

        $this->assertDatabaseHas('contact_settings', ['email' => 'info@apartmanstratos.cz']);

        $settings->update(['email' => 'new@example.com']);

        $this->assertDatabaseHas('contact_settings', ['id' => $settings->id, 'email' => 'new@example.com']);
    }
}
