<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_switch_locale_rewrites_previous_path(): void
    {
        $resp = $this->withHeaders(['referer' => 'http://localhost/en/contact'])
            ->get('/locale/cs');

        $resp->assertRedirect('/cs/contact');
    }

    public function test_invalid_locale_returns_404(): void
    {
        $this->get('/locale/xx')->assertStatus(404);
    }
}
