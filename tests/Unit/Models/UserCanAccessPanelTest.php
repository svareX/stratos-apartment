<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCanAccessPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_panel_returns_true_for_any_panel()
    {
        $user = User::create([
            'name' => 'Panel User',
            'email' => 'panel@example.com',
            'password' => bcrypt('secret'),
        ]);

        $mockPanel = $this->createMock(Panel::class);

        $this->assertTrue($user->canAccessPanel($mockPanel));
    }
}
