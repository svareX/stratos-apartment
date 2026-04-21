<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\InstagramPost;

class InstagramPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_instagram_post(): void
    {
        $post = InstagramPost::create([
            'instagram_id' => '12345',
            'image_url' => 'https://example.com/img.jpg',
            'url' => 'https://instagram.com/p/12345',
            'caption' => 'Nice view',
            'posted_at' => now(),
        ]);

        $this->assertDatabaseHas('instagram_posts', ['id' => $post->id, 'instagram_id' => '12345']);

        $post->update(['caption' => 'Updated caption']);
        $this->assertDatabaseHas('instagram_posts', ['id' => $post->id, 'caption' => 'Updated caption']);

        $post->delete();
        $this->assertDatabaseMissing('instagram_posts', ['id' => $post->id]);
    }
}
