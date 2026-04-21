<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\TermsPage;
use App\Livewire\CookiesPage;

class TermsCookiesComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_terms_and_cookies_components_render_without_errors(): void
    {
        Livewire::test(TermsPage::class)
            ->assertHasNoErrors();

        Livewire::test(CookiesPage::class)
            ->assertHasNoErrors();
    }
}
