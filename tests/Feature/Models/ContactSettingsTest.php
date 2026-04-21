<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\ContactSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_returns_existing_or_creates_default(): void
    {
        // Ensure no settings exist initially
        $this->assertCount(0, ContactSettings::all());

        $settings = ContactSettings::current();

        $this->assertInstanceOf(ContactSettings::class, $settings);
        $this->assertDatabaseHas('contact_settings', ['email' => 'info@apartmanstratos.cz']);

        // Calling current() again should return the same record
        $again = ContactSettings::current();
        $this->assertEquals($settings->id, $again->id);

        // Update and verify address accessor (appends)
        $settings->update(['address_en' => 'New Addr']);
        $this->assertEquals('New Addr', $settings->fresh()->address_en);
    }

    public function test_current_creates_default_and_updates(): void
    {
        $settings = ContactSettings::current();

        $this->assertDatabaseHas('contact_settings', ['email' => 'info@apartmanstratos.cz']);

        $settings->update(['email' => 'new@example.com']);

        $this->assertDatabaseHas('contact_settings', ['id' => $settings->id, 'email' => 'new@example.com']);
    }
}
