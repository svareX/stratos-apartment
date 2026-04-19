<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaceholderRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_terms_and_cookies_placeholders_render(): void
    {
        $terms = new \App\Livewire\TermsPage();
        $view = $terms->placeholder();
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);

        $cookies = new \App\Livewire\CookiesPage();
        $view2 = $cookies->placeholder();
        $this->assertInstanceOf(\Illuminate\View\View::class, $view2);
    }

    public function test_contact_placeholder_renders(): void
    {
        $comp = new \App\Livewire\ContactComponent();
        $view = $comp->placeholder();
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
    }
}
