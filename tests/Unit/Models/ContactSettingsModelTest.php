<?php

namespace Tests\Unit\Models;

use App\Models\ContactSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSettingsModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_creates_defaults_and_translates_address()
    {
        $cs = ContactSettings::current();

        $this->assertNotNull($cs->email);
        app()->setLocale('de');
        $this->assertStringContainsString('Tschechische', $cs->address);
    }
}
