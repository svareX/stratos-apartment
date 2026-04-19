<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_user(): void
    {
        $user = User::create([
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertDatabaseHas('users', ['email' => 'tester@example.com']);

        $user->update(['name' => 'Updated Tester']);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Tester']);

        $user->delete();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
