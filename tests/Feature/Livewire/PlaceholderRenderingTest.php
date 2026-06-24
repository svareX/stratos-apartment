<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\ContactComponent;
use App\Livewire\CookiesPage;
use App\Livewire\TermsPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\View\View;
use Tests\TestCase;

class PlaceholderRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_terms_and_cookies_placeholders_render(): void
    {
        $terms = new TermsPage;
        $view = $terms->placeholder();
        $this->assertInstanceOf(View::class, $view);

        $cookies = new CookiesPage;
        $view2 = $cookies->placeholder();
        $this->assertInstanceOf(View::class, $view2);
    }

    public function test_contact_placeholder_renders(): void
    {
        $comp = new ContactComponent;
        $view = $comp->placeholder();
        $this->assertInstanceOf(View::class, $view);
    }
}
