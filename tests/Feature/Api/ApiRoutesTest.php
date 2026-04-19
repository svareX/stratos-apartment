<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_user_requires_authentication(): void
    {
        $this->getJson('/api/user')->assertStatus(401);
    }
}
