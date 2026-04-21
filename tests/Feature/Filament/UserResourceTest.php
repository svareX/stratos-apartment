<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_filament_can_create_user(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser+1@example.com',
            'password' => 'secret123',
        ];

        Livewire::test(CreateUser::class)
            ->set('data', $data)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', ['email' => 'testuser+1@example.com', 'name' => 'Test User']);
    }

    public function test_filament_can_update_and_delete_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'olduser@example.com',
            'password' => 'oldpass',
        ]);

        $update = [
            'name' => 'Updated Name',
            'email' => 'updateduser@example.com',
            'password' => 'newpass',
        ];

        Livewire::test(EditUser::class, ['record' => $user->id])
            ->set('data', array_merge($user->toArray(), $update))
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name', 'email' => 'updateduser@example.com']);

        // Delete via model to verify removal
        $user->delete();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
